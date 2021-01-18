<?php

namespace Lessmore92\Ripple\Transaction;

use Exception;
use Lessmore92\Buffer\Buffer;
use Lessmore92\RippleBinaryCodec\RippleBinaryCodec;
use Lessmore92\RippleKeypairs\RippleKeyPairs;

define('TRANSACTION_ID', 0x54584e00); // 'TXN'

/**
 * User: Lessmore92
 * Date: 1/12/2021
 * Time: 3:14 PM
 */
class Sign
{
    private $keypair;
    /**
     * @var RippleKeyPairs
     */
    private $binaryCodec;

    public function __construct()
    {
        $this->keypair     = new RippleKeyPairs();
        $this->binaryCodec = new RippleBinaryCodec();
    }

    public function sign($txJson, $secret, $options = [])
    {
        return $this->signWithKeypair($txJson, $this->keypair->deriveKeypair($secret), $options);
    }

    public function signWithKeypair($txJson, $keypair, $options)
    {
        if (isset($txJson['TxnSignature']) || isset($txJson['Signers']))
        {
            throw new Exception('txJSON must not contain "TxnSignature" or "Signers" properties');
        }

        //fee checking moved to Ripple.php
        //$this->checkFee($txJson['fee']);

        $txToSignAndEncode                  = $txJson;
        $txToSignAndEncode['SigningPubKey'] = isset($options['signAs']) ? '' : $keypair['public'];

        if (isset($options['signAs']))
        {
            $signer['Account']            = $options['signAs'];
            $signer['SigningPubKey']      = $keypair['public'];
            $signer['TxnSignature']       = $this->computeSignature(
                $txToSignAndEncode,
                $keypair['private'],
                $options['signAs']
            );
            $txToSignAndEncode['Signers'] = [['Signer' => $signer]];
        }
        else
        {
            $txToSignAndEncode['TxnSignature'] = $this->computeSignature(
                $txToSignAndEncode,
                $keypair['private']
            );
        }

        $serialized = $this->binaryCodec->encode($txToSignAndEncode);

        return [
            'signedTransaction' => $serialized,
            'id'                => $this->computeBinaryTransactionHash($serialized)
        ];
    }

    public function computeSignature(array $tx, string $privateKey, string $signAs = null)
    {
        $signingData = $signAs
            ? $this->binaryCodec->encodeForMultisigning($tx, $signAs)
            : $this->binaryCodec->encodeForSigning($tx);

        return $this->keypair->sign(Buffer::hex($signingData)
                                          ->getBinary(), $privateKey);
    }

    public function computeBinaryTransactionHash(string $serializedTx)
    {
        $prefix = Buffer::int(TRANSACTION_ID)
                        ->getHex()
        ;

        return Buffer::hex(hash('sha512', Buffer::hex($prefix . $serializedTx)
                                                ->getBinary()))
                     ->slice(0, 32)
                     ->getHex()
            ;
    }
}
