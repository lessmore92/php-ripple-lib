<?php
/**
 * User: Lessmore92
 * Date: 1/14/2021
 * Time: 3:24 PM
 */

namespace Lessmore92\Ripple\Model\Response;

use Lessmore92\Ripple\Foundation\Contracts\ResponseModelInterface;
use Lessmore92\Ripple\Foundation\ResponseModel;
use Lessmore92\Ripple\Model\Base\Balance;
use Lessmore92\Ripple\Utils;

class AccountInfo extends ResponseModel implements ResponseModelInterface
{
    /**
     * @var string
     */
    public $account;
    /**
     * @var Balance
     */
    public $balance;
    /**
     * @var string
     */
    public $flags;
    /**
     * @var
     */
    public $ledgerEntryType;
    public $ownerCount;
    public $previousTxnID;
    public $previousTxnLgrSeq;
    public $sequence;
    public $index;

    public static function fromJson($json)
    {
        $instance                    = new self();
        $instance->account           = Utils::arrayGet($json, 'Account');
        $instance->balance           = Balance::fromDrops(Utils::arrayGet($json, 'Balance', ''));
        $instance->flags             = Utils::arrayGet($json, 'Flags');
        $instance->ledgerEntryType   = Utils::arrayGet($json, 'LedgerEntryType');
        $instance->ownerCount        = Utils::arrayGet($json, 'OwnerCount');
        $instance->previousTxnID     = Utils::arrayGet($json, 'PreviousTxnID');
        $instance->previousTxnLgrSeq = Utils::arrayGet($json, 'PreviousTxnLgrSeq');
        $instance->sequence          = Utils::arrayGet($json, 'Sequence');
        $instance->index             = Utils::arrayGet($json, 'index');

        return $instance;
    }
}
