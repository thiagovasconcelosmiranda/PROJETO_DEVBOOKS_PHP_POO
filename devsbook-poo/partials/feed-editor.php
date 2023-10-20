<?php
 $firstName = current(explode( ' ', $userInfo->name));
?>

<div class="box feed-new">
        <div class="box-body">
            <div class="feed-new-editor m-10 row">
                <div class="feed-new-avatar">
                    <img src="<?=$base;?>/media/avatars/<?=$userInfo->avatar;?>" />
                </div>
                <div class="feed-new-input-placeholder">O que você está pensando, <?=$firstName;?>?</div>
                <div class="feed-new-input" contenteditable="true"></div>
                <div class="feed-new-send">
                    <img src="<?=$base;?>/assets/images/send.png" />
                </div>
                <form class='feed-new-form' method="POST" action="<?=$base;?>/feed_editor_action.php">
                   <input type="hidden" name="body">
                </form>
             </div>
        </div>
    </div>
    <script>
        let feedInput = document.querySelector('.feed-new-input');
        let feedSubimit = document.querySelector('.feed-new-send');
        let feedForm = document.querySelector('.feed-new-form');

        feedSubimit.addEventListener('click',()=>{
            var value = feedInput.innerText.trim();

            feedForm.querySelector('input[name=body]').value = value;
            feedForm.submit();
        })

    </script>

   