<div class="comments">
<div class="comments-disclaimer">AIGA encourages thoughtful, responsible discourse. Please add comments judiciously, and refrain from maligning any individual, institution or body of work. <a target="_blank" href="http://www.aiga.org/faqs-commenting-policy/">Read our policy</a> on commenting.</div>

<?php comments_template(); ?>

<?php if(WP_DEBUG) { ?>
    <script type="text/javascript">
        var disqus_developer = 1; // developer mode is on
    </script>
<?php } ?>

<script type="text/javascript">

function disqus_config() {
    // Disqus may take a bit to render, so update the grid layout once it finishes for proper layout
    this.callbacks.onReady.push(function() {

        // Give a little of extra time, as there is no onRender callback anymore
        setTimeout(function() {
            jQuery.ikit_two.grid.layout();
        }, 500);
    });
}

</script>

</div>