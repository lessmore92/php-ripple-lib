<?php
/**
 * User: Lessmore92
 * Date: 1/12/2021
 * Time: 2:21 PM
 */

namespace Lessmore92\Ripple;

use BN\BN;
use Lessmore92\Ripple\Foundation\Contracts\HttpClientInterface;
use Lessmore92\Ripple\GuzzleClient\Http;
use Lessmore92\Ripple\GuzzleClient\Request;
use Lessmore92\Ripple\Model\Response\AccountInfo;
use Lessmore92\Ripple\Transaction\Sign;

class RippleAPI
{
    protected $maxFeeXRP;
    protected $feeCushion = null;
    /**
     * @var HttpClientInterface
     */
    protected $http;
    /**
     * @var string $server
     */
    protected $server;
    /**
     * @var float
     */
    protected $timeout;

    public function __construct($options = [], HttpClientInterface $http = null)
    {
        $this->maxFeeXRP = isset($options['maxFeeXRP']) ? strval($options['maxFeeXRP']) : '2';

        if (!isset($options['server']))
        {
            throw new \Exception('Json RPC server not provided.');
        }
        $this->server  = $options['server'];
        $this->timeout = isset($options['timeOut']) ? $options['timeOut'] : 3;

        if (is_null($http))
        {
            $http = new Http($this->server, $this->timeout);
        }
        $this->http = $http;
    }

    public function accountInfo(string $account, $strict = true, $ledgerIndex = "current", $queue = true)
    {
        return AccountInfo::fromJson($this->send('account_info', [
            'account'      => $account,
            'strict'       => $strict,
            'ledger_index' => $ledgerIndex,
            '   queue'     => $queue,
        ])['account_data']);
    }

    public function send($method, $params = null)
    {
        $req = new Request();

        $body['method'] = $method;
        $body['params'] = [
            $params
        ];

        $req->setJson($body);
        $res = $this->http->send($req);

        $json = json_decode($res->getBody(), true);
        return $json['result'];
    }

    public function accountTx(string $account, $options = [])
    {
        $options['account'] = $account;

        return $this->send('account_tx', $options);
    }

    public function tx(string $transaction, bool $binary = false)
    {
        $options['transaction'] = $transaction;

        return $this->send('tx', $options);
    }

    public function submit(string $tx_blob)
    {
        return $this->send('submit', ['tx_blob' => $tx_blob]);
    }

    public function getFee($cushion = null)
    {
        if (is_null($cushion))
        {
            $cushion = $this->feeCushion;
        }

        if (is_null($cushion))
        {
            $cushion = 1.2;
        }

        $serverInfo = $this->serverInfo();
        $baseFeeXrp = $serverInfo['validated_ledger']['base_fee_xrp'];
        $fee        = $baseFeeXrp * $serverInfo['load_factor'] * $cushion;

        $fee = min($this->maxFeeXRP, $fee);
        return number_format($fee, 6, '.', '');
    }

    public function serverInfo()
    {
        return Utils::arrayGet($this->send('server_info'), 'info');
    }

    public function sign(string $txJson, string $secret, array $options = [])
    {
        $tx = json_decode($txJson, true);
        $this->checkFee($tx['Fee']);
        $sign = new Sign();
        return $sign->sign($tx, $secret, $options);
    }

    protected function checkFee(string $fee)
    {
        $fee         = new BN($fee);
        $maxFeeDrops = Utils::xrpToDrops($this->maxFeeXRP);
        if ($fee->gt(new BN($maxFeeDrops)))
        {
            throw new \Exception("Fee should not exceed '{$maxFeeDrops}'. To use a higher fee, set 'maxFeeXRP' in the RippleAPI constructor.");
        }
    }
}
