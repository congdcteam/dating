<?php

/*!
 * https://raccoonsquare.com
 * raccoonsquare@gmail.com
 *
 * Copyright 2012-202 Demyanchuk Dmitry (raccoonsquare@gmail.com)
 */

    if (!$auth::isSession()) {

        header('Location: /');
        exit;
    }

    $balance = auth::getCurrentUserBalance();

    $gifts = new gift($dbo);
    $gifts->setRequestFrom(auth::getCurrentUserId());

    $result = $gifts->db_get(0);

    ?>
    <div class="row separator-bottom">
        <div class="gallery-intro-header col-12 p-3 py-4 m-0">

            <h1 class="gallery-title"><?php echo $LANG['label-you-balance']; ?>: <span class="account-balance" data-balance="<?php echo $balance; ?>"><?php echo $balance; ?></span> <?php echo $LANG['label-credits']; ?></h1>

            <a class="add-button button green" href="/account/balance">
                <?php echo $LANG['action-buy']; ?>
            </a>

        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <?php

            foreach ($result['items'] as $key => $item) {

                ?>

                <div class="col-4 col-sm-4 col-md-4 col-lg-2 my-2 p-2 gift" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['cost']; ?>">

                    <img src="<?php echo $item['imgUrl']; ?>" style="z-index: 2;">

                    <span class="gift-price"><?php echo $item['cost']." ".$LANG['label-credits']; ?></span>

                </div>


                <?php
            }

            ?>
        </div>
    </div>
    <?php
