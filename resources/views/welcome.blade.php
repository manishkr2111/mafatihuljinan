<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Title</title>
    <script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>

</head>
<body><!-- Local TinyMCE -->

<textarea id="myEditor" class="tinymce-editor"></textarea>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#myEditor', // targets all textareas with this class
            height: 400,
            plugins: 'link image code lists table',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            license_key: 'gpl', // self-hosted GPL mode
            menubar: true,
            branding: false, // hides "Powered by TinyMCE" branding
            setup: function(editor) {
                editor.on('init', function() {
                    console.log('TinyMCE initialized:', editor.id);
                });
            }
        });
    });
</script>
</body>
</html>
