<?php require_once APPROOT."/views/inc/header.php" ?>
    <a href="<?php echo URLROOT;?>/posts" class="btn btn-primary"><i class="fa fa-backward"></i> Back</a>
    <br>
    <h1><?php echo $data["post"]->title ?></h1>
    <div class="bg-secondary p-2 mb-3 text-white">
        Written By <?php echo $data["user"]->name; ?> on <?php echo $data["post"]->created_at; ?>
    </div>
    <p><?php echo $data["post"]->body; ?></p>
    <hr>
    <?php if($data["post"]->user_id == $_SESSION["user_id"]) :?>
        <a class="btn btn-dark" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data["post"]->id; ?>">Edit</a>
        <form class="pull-right" action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data["post"]->id ?>" method="post">
            <input type="submit" value="Delete" class="btn btn-danger">
        </form>
    <?php endif; ?>
<?php require_once APPROOT."/views/inc/footer.php" ?>