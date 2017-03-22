<?php
use yii\helpers\Url;

?>

<div class="panel-title box-title" style="border-bottom:none">
<span><strong>只言片语</strong></span>
    <span class="pull-right"><a href="" class="font-12">更多>></a></span>
</div>
<div class="pannel-boy">
    <form  id="w0" action="/" method="post">
    <div class="form-group input-group field-feed-content required">
    <textarea name="" id="feed-content" cols="30" rows="10" class="form-control" name="content">

    </textarea>
			<span class="input-group-btn">
				<button type="button" data-url="<?=Url::to(['site/add-feed'])?>" class='btn btn-success btn-feed j-feed'>发布</button>
			</span>
		</div>

	</form>
<?php if (!empty($data['feed'])):?>
    <ul class="media-list media-feed feed-index ps-container ps-active-y">
        <?php foreach ($data['feed'] as $list):?>
        <li class="media">
            <div class="media-left"><a href="#" rel="author" data-original-title="" title="">
            <img alt="" class="avatar-img" style="width:37px;height:37px;" src="statics/images/avatar/small.jpg"/></a></div>
            <div class="media-body">
                <div class="media-content">
                    <a href="#" ><?=$list['user']['username']?>: </a>
                    <span><?=$list['content']?></span>
                </div>
                <div class="media-action">
                    <?=date('Y-m-d h:i:s',$list['created_at'])?>
                </div>
            </div>
            
        </li>
        <?php endforeach;?>
    </ul>
    <?php endif;?>
</div>
