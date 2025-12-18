<!--========== Start scrollTop ==============-->
<button class="scroll-top" id="scrollTopBtn" title="Kembali ke atas" type="button">
    <svg class="progress-circle" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" fill="none" stroke-width="4" stroke="#BC1D24"></path>
    </svg>
    <span class="scroll-top-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 19V5"></path>
            <path d="M5 12l7-7 7 7"></path>
        </svg>
    </span>
</button>

<script>
// Inline scroll-top script untuk memastikan berjalan
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('scrollTopBtn');
        if (!btn) return;
        
        var progressPath = btn.querySelector('.progress-circle path');
        var pathLength = 0;
        
        if (progressPath) {
            try {
                pathLength = progressPath.getTotalLength();
                progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
                progressPath.style.strokeDashoffset = pathLength;
            } catch(e) {}
        }
        
        function updateButton() {
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var docHeight = document.documentElement.scrollHeight - window.innerHeight;
            
            if (scrollTop > 200) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
            
            if (progressPath && pathLength > 0 && docHeight > 0) {
                var progress = pathLength - (scrollTop * pathLength / docHeight);
                progressPath.style.strokeDashoffset = progress;
            }
        }
        
        window.addEventListener('scroll', updateButton);
        updateButton();
        
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
})();
</script>
<!--========== End scrollTop ==============-->
