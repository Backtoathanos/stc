{{-- Include this in views that need JavaScript base URL --}}
<script>
    // Ensure appBaseUrl is defined (fallback if not set in head)
    if (typeof window.appBaseUrl === 'undefined') {
        window.appBaseUrl = '{{ url("/") }}';
    }
    if (typeof window.appBasePath === 'undefined') {
        window.appBasePath = '{{ config("app.base_path", "/") }}';
    }
    
    // Helper function to build URLs
    window.buildUrl = function(path) {
        // Remove leading slash if present
        path = path.replace(/^\//, '');
        return window.appBaseUrl + '/' + path;
    };
</script>

