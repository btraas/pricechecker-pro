<?php include('inc/header.php'); ?>

<?php include('inc/lookup/upc-input.php'); ?>
            
<!-- Add spacer to push Footer down when not enough content -->
<div class="mdl-layout-spacer" style='margin-bottom: 56px'>
    <ul class="share-buttons">
        <li>
            <a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fpricechecker.pro%3A444%2F&t=" title="Share on Facebook" target="_blank" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;">
                <img alt="Share on Facebook" src="images/social_flat_rounded_rects_svg/Facebook.svg">
            </a>
        </li>
        <li>
            <a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fpricechecker.pro%3A444%2F&text=:%20http%3A%2F%2Fpricechecker.pro%3A444%2F" target="_blank" title="Tweet" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20'  + encodeURIComponent(document.URL)); return false;">
                <img alt="Tweet" src="images/social_flat_rounded_rects_svg/Twitter.svg">
            </a>
        </li>
        <li>
            <a href="https://plus.google.com/share?url=http%3A%2F%2Fpricechecker.pro%3A444%2F" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;">
                <img alt="Share on Google+" src="images/social_flat_rounded_rects_svg/Google+.svg">
            </a>
        </li>
        <li>
            <a href="mailto:?subject=&body=:%20http%3A%2F%2Fpricechecker.pro%3A444%2F" target="_blank" title="Send email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;">
                <img alt="Send email" src="images/social_flat_rounded_rects_svg/Email.svg">
            </a>
        </li>
    </ul>
</div>
<?php include('inc/footer.php'); ?>
