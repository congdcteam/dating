<?php

    /*!
     * https://raccoonsquare.com
     * raccoonsquare@gmail.com
     *
     * Copyright 2012-2022 Demyanchuk Dmitry (raccoonsquare@gmail.com)
     */

    if (!defined("APP_SIGNATURE")) {

        header("Location: /");
        exit;
    }

    if (!$auth->authorize(auth::getCurrentUserId(), auth::getAccessToken())) {

        header('Location: /');
        exit;
    }

    $profileId = $helper->getUserId($request[0]);

    $profile = new profile($dbo, $profileId);

    $profile->setRequestFrom(auth::getCurrentUserId());
    $profileInfo = $profile->get();

    if ($profileInfo['error']) {

        include_once("html/error.inc.php");
        exit;
    }

    if ($profileInfo['state'] != ACCOUNT_STATE_ENABLED) {

        include_once("html/error.inc.php");
        exit;
    }

    $access_denied = false;

    if ($profileInfo['id'] != auth::getCurrentUserId() && !$profileInfo['friend'] && $profileInfo['allowShowMyLikes'] == 1) {

        $access_denied = true;
    }

    $items_all = $profileInfo['likesCount'];
    $items_loaded = 0;

    if (!empty($_POST) && !$access_denied) {

        $itemId = isset($_POST['itemId']) ? $_POST['itemId'] : 0;
        $loaded = isset($_POST['loaded']) ? $_POST['loaded'] : 0;

        $itemId = helper::clearInt($itemId);
        $loaded = helper::clearInt($loaded);

        $result = $profile->getFans($itemId);

        $items_loaded = count($result['items']);

        $result['items_loaded'] = $items_loaded + $loaded;
        $result['items_all'] = $items_all;

        if ($items_loaded != 0) {

            ob_start();

            foreach ($result['items'] as $key => $value) {

                draw::peopleCardviewItem($value, $LANG, true, $value['age'], $LANG['label-select-age'], "red");
            }

            $result['html'] = ob_get_clean();

            if ($result['items_loaded'] < $items_all) {

                ob_start();

                ?>

                <header class="top-banner loading-banner p-0 pt-3">

                    <div class="prompt">
                        <button onclick="Items.more('/<?php echo $profileInfo['username']; ?>/likes', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                    </div>

                </header>

                <?php

                $result['html2'] = ob_get_clean();
            }
        }

        echo json_encode($result);
        exit;
    }

    if ($profileInfo['id'] == auth::getCurrentUserId()) {

        $page_id = "my-likes";

    } else {

        $page_id = "likes";
    }

    $css_files = array("my.css", "account.css");
    $page_title = $LANG['page-likes']." | ".APP_TITLE;

    include_once("html/common/site_header.inc.php");

?>

<body class="width-page">

    <?php

        include_once("html/common/site_topbar.inc.php");
    ?>

    <div class="wrap content-page">

        <div class="main-column row">

            <?php

                include_once("html/common/site_sidenav.inc.php");
            ?>

            <div class="col-lg-9 col-md-12" id="content">

                <div class="main-content">

                    <div class="standard-page page-title-content">

                        <div class="page-title-content-inner">
                            <?php echo $LANG['page-likes']; ?>
                        </div>
                        <div class="page-title-content-bottom-inner">
                            <?php

                                if ($profileInfo['id'] == auth::getCurrentUserId()) {

                                    echo $LANG['label-likes-sub-title'];

                                } else {

                                    echo sprintf($LANG['label-likes-sub-title-2'], '<strong>'.$profileInfo['fullname'].'</strong>'); ;
                                }
                            ?>
                        </div>
                    </div>

                    <div class="content-list-page empty-banner-container">

                        <?php

                            $result = $profile->getFans(0);

                            $items_loaded = count($result['items']);

                            if ($items_loaded == 0 && !$access_denied) {

                                ?>

                                    <header class="top-banner info-banner empty-list-banner">


                                    </header>

                                <?php
                            }
                        ?>

                    </div>

                </div>

                <div class="main-content cardview-content">

                    <div class="standard-page cardview-container items-container">

                        <?php

                        if ($access_denied) {

                            ?>

                            <div class="card information-banner border-0">
                                <div class="card-header">
                                    <div class="card-body">
                                        <h5 class="m-0"><?php echo $LANG['label-error-permission']; ?></h5>
                                    </div>
                                </div>
                            </div>

                            <?php

                        } else {

                            if ($items_loaded != 0) {

                                ?>

                                <div class="cardview items-view">

                                    <?php

                                    foreach ($result['items'] as $key => $value) {

                                        draw::peopleCardviewItem($value, $LANG, true, $value['age'], $LANG['label-select-age'], "red");
                                    }
                                    ?>
                                </div>

                                <?php

                                if ($items_all > 20) {

                                    ?>

                                    <header class="top-banner loading-banner p-0 pt-3">

                                        <div class="prompt">
                                            <button onclick="Items.more('/<?php echo $profileInfo['username']; ?>/likes', '<?php echo $result['itemId']; ?>'); return false;" class="button more loading-button"><?php echo $LANG['action-more']; ?></button>
                                        </div>

                                    </header>

                                    <?php
                                }
                            }
                        }
                        ?>

                    </div>
                </div>

            </div>

        </div>

    </div>

        <?php

            include_once("html/common/site_footer.inc.php");
        ?>

        <script type="text/javascript">

            var items_all = <?php echo $items_all; ?>;
            var items_loaded = <?php echo $items_loaded; ?>;

        </script>

</body>
</html>