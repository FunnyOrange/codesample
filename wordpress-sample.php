<?php get_header(); ?>

<div class="course-content clearfix popup-container">
	<?php if(!empty(ThemexCourse::$data['questions'])) { ?>
	<div class="twelvecol column">
	<?php } else { ?>
	<div class="fullwidth-section">
	<?php } ?>
		<?php if(!empty(ThemexCourse::$data['lessons'])) { ?>
		<?php if(ThemexCourse::isMember()) { ?>
		<div class="course-progress">
			<span style="width:<?php echo ThemexCourse::$data['progress']; ?>%;"></span>
		</div>
		<?php } ?>
		<div class="lessons-listing">
			<?php
			global $lesson_count;
			$lesson_count = 0;
			$beginner = array();
			$intermediate = array();
			$advanced = array();
			$rest = array();
			foreach (ThemexCourse::$data['lessons'] as $i=>&$lesson){
                if (get_post_meta($lesson->ID, '_lesson_difficulty', true) == 'beginner')
                    $beginner[] = $lesson;
                elseif (get_post_meta($lesson->ID, '_lesson_difficulty', true) == 'intermediate')
                    $intermediate[] = $lesson;
                elseif (get_post_meta($lesson->ID, '_lesson_difficulty', true) == 'advanced')
                    $advanced[] = $lesson;
                else
                    $rest[] = $lesson;
            }

            $begStop = false;
            $intStop = false;
            $advStop = false;
            $lessons = array_merge($beginner, $intermediate, $advanced, $rest);
			foreach($lessons as $index=>$lesson) {
                $lesson_count++;
			    if (get_post_meta($lesson->ID, '_lesson_difficulty', true) == 'beginner') {
			        if (!$begStop)
                        echo '<h3 class="lesson-difficulty">Beginner</h3>';
                    $begStop = true;
                    get_template_part('content', 'lesson-saga');
                    if ($index == (count($beginner) - 1))
                        echo '<br>';
                }
                elseif (get_post_meta($lesson->ID, '_lesson_difficulty', true) == 'intermediate'){
			        if (!$intStop)
			            echo '<h3 class="lesson-difficulty">Intermediate</h3>';
			        $intStop = true;
                    get_template_part('content', 'lesson-saga');
                    if ($index - count($beginner) == count($intermediate) - 1)
                        echo '<br>';
                }
                elseif (get_post_meta($lesson->ID, '_lesson_difficulty', true) == 'advanced'){
                    if (!$advStop)
                        echo '<h3 class="lesson-difficulty">Advanced</h3>';
                    $advStop = true;
                    get_template_part('content', 'lesson-saga');
                    if ($index - (count($beginner)+count($intermediate)) == count($advanced) - 1)
                        echo '<br><br><br>';
                }
                else {
                    get_template_part('content', 'lesson-saga');
                }
			} ?>
		</div>
		<?php } ?>
	</div>
	<?php
    	// @disabled for course single view
	 if(false && !empty(ThemexCourse::$data['questions'])) { ?>
	<div class="course-questions fivecol column last">
		<h1><?php _e('Questions', 'academy'); ?></h1>
		<ul class="styled-list style-2 bordered">
		<?php foreach(ThemexCourse::$data['questions'] as $question) {?>
		<li><a href="<?php echo get_comment_link($question->comment_ID); ?>"><?php echo get_comment_meta($question->comment_ID, 'title', true); ?></a></li>
		<?php } ?>
		</ul>
	</div>
	<?php } ?>
	<?php if((!ThemexCourse::isSubscriber() || !ThemexCourse::isMember()) && !ThemexCourse::isAuthor()) { ?>
	<div class="popup hidden">
		<?php if(!ThemexCourse::isSubscriber()) { ?>
		<h2 class="popup-text"><?php _e('Subscribe to view this content', 'academy'); ?></h2>
		<?php } else { ?>
		<h2 class="popup-text"><?php _e('Take a course to view this content', 'academy'); ?></h2>
		<?php } ?>
	</div>
	<!-- /popup -->
	<?php }
	?>
</div>

<?php get_template_part('module', 'course-author-saga'); ?>

<!-- /course content -->
<?php get_template_part('module', 'related'); ?>

<?php wp_reset_query();

?>

<div class="block spaced-md-top"></div>

<?php get_footer(); ?>
