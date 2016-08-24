<div class="jumbotron">
    <div class="container">
        <h1><?php echo $this->Csz_model->getLabelLang('gallery_header') ?></h1>
        <h3><?php echo $this->Csz_model->getLabelLang('gallery_albumlist') ?></h3>
    </div>
</div>
<div class="container">
<div class="row">
    <div class="col-md-12">
        <?php if($gallery === FALSE){ 
            echo '<center><h2>' . $this->Csz_model->getLabelLang('gallery_not_found') . '</h2></center>';
        }else{ ?>
            <div class="row">
            <?php foreach ($gallery as $value) { 
                $i = 1; ?>
                <div class="col-md-4">
                    <div class="thumbnail">
                        <?php $f_img = $this->Gallery_model->getFirstImgs($value['gallery_db_id']); ?>
                        <a href="<?php echo BASE_URL.'/plugin/gallery/view/'.$value['gallery_db_id'].'/'.$value['url_rewrite'] ?>" title="<?php echo $value['album_name'] ?>"><img class="img-responsive img-thumbnail" src="<?php echo $f_img?>" alt="<?php echo $value['album_name'] ?>"></a>
                        <hr>
                        <div class="caption">
                            <a href="<?php echo BASE_URL.'/plugin/gallery/view/'.$value['gallery_db_id'].'/'.$value['url_rewrite'] ?>" title="<?php echo $value['album_name'] ?>"><h2><?php echo $value['album_name'] ?></h2></a>
                            <p><small><b><?php echo $this->Csz_model->getLabelLang('article_postdate') ?>: <?php echo $value['timestamp_create'] ?></b></small></p>
                            <p><?php echo $value['short_desc'] ?></p>
                            <br><p><a href="<?php echo BASE_URL.'/plugin/gallery/view/'.$value['gallery_db_id'].'/'.$value['url_rewrite'] ?>" class="btn btn-primary" role="button" title="<?php echo $value['album_name'] ?>"><?php echo $this->Csz_model->getLabelLang('gellery_view_btn') ?></a></p>
                        </div>
                    </div>
                </div>
            <?php if($i%3 == 0){ ?>
            </div><div class="row">
            <?php } $i++; } ?>
            </div>
            <br><br>
            <?php echo $this->pagination->create_links(); ?> <b><?php echo $this->Csz_model->getLabelLang('total_txt') . ' ' . $total_row . ' ' . $this->Csz_model->getLabelLang('records_txt'); ?></b>
        <?php } ?>
    </div>
</div>
</div>