<?php
  $fullScreen     = $helper->get('full-screen');
  $blockBg        = $helper->get('block-bg');
  $blockTitle     = $helper->get('block-title');
  $blockVideo     = $helper->get('block-video');

  $video_src    = '';
  $video_link    = '';
  if ($blockVideo) {
    $arr = preg_split ('/=/', $blockVideo, 2);
    if (count($arr) == 2) {    
      switch (trim($arr[0])) {
        case 'vimeo':
          $video_src = '//player.vimeo.com/video/' . trim($arr[1]) . '?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1&amp;loop=1';
          $video_link = trim($arr[1]);
          break;
        case 'youtube':
          $video_src = '//www.youtube.com/embed/' . trim($arr[1]) . '?playlist=' . trim($arr[1]) . '&amp;autoplay=1&amp;loop=1&amp;html5=1';
           $video_link = trim($arr[1]);
          break;
        default:
          break;
      }
    }
  }
  //Ad js for testimonial module custom
  $document = JFactory::getDocument();
  $document->addScript(T3_TEMPLATE_URL . '/js/video.background.js');
?>

<div class="style-1 block-video text-center <?php echo $fullScreen; ?>" id="block-video-<?php echo $module->id; ?>" style="background-image: url(<?php echo $blockBg ?>);">
  <div class="block-content container">
    <h3 class="block-title col-md-8 col-md-offset-2"><?php echo $blockTitle; ?></h3>
    <div class="block-play"><a class="play-video" href="javascript:void(0);" title=""><i class="fa fa-play"></i></a></div>
    <iframe style="display: none;" width="560" height="315" frameborder="0" allowfullscreen="" src="<?php echo $video_src ?>"></iframe>
  </div>
  <div class="mask" style="display: none;"></div>
  <div style="display: none;" class="box-video">
    <div class="close-video"></div>
  </div>
</div>