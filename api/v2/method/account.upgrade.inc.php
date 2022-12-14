<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

if (!empty($_POST)) {

    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 0;
    $accessToken = isset($_POST['accessToken']) ? $_POST['accessToken'] : '';

    $credits = isset($_POST['credits']) ? $_POST['credits'] : 0;
    $upgradeType = isset($_POST['upgradeType']) ? $_POST['upgradeType'] : 0;

    $credits = helper::clearInt($credits);
    $upgradeType = helper::clearInt($upgradeType);

    $auth = new auth($dbo);

    if (!$auth->authorize($accountId, $accessToken)) {

        api::printError(ERROR_ACCESS_TOKEN, "Error authorization.");
    }

    $result = array(
        "error" => true,
        "error_code" => ERROR_UNKNOWN
    );

    $account = new account($dbo, $accountId);

    $balance = $account->getBalance();

    if ($balance >= $credits) {

        switch ($upgradeType) {

            case PA_BUY_VERIFIED_BADGE: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setVerify(1);

                break;
            }

            case PA_BUY_GHOST_MODE: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setGhost(1);

                break;
            }

            case PA_BUY_DISABLE_ADS: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setAdmob(1);

                break;
            }

            case PA_BUY_PRO_MODE: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setPro(1);

                break;
            }

            case PA_BUY_MESSAGE_PACKAGE: {

                $account->setBalance($account->getBalance() - $credits);

                $result = $account->setFreeMessagesCount($account->getFreeMessagesCount() + 100);

                break;
            }

            default: {

                break;
            }
        }

        if (!$result['error']) {

            $payments = new payments($dbo);
            $payments->setRequestFrom($accountId);
            $payments->create($upgradeType, PT_CREDITS, $credits);
            unset($payments);
        }
    }

    echo json_encode($result);
    exit;
}
