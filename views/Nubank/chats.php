<?php include_once('views/includes/top.php'); ?>

    <form action="/?upload" method="post" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
        <input class="form-control mb-3" type="file" name="path[]" multiple="multiple" id="path" placeholder="Ex.: C:\Users\user\Desktop" required>
        <input type="file" name="chat[]" id="chat" multiple>
        <button type="submit">Upload</button>
    </form>
    
<?php include_once('views/includes/bottom.php'); ?>