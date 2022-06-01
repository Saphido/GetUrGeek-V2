<head>
    <link href="src/js/cropper/cropper.css" rel="stylesheet">
    <script src="src/js/cropper/cropper.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<input type="file" name="image" id="image" onchange="readURL(this);" />
<div class="image_container">
    <img id="blah" alt="your image" />
</div>
<div id="cropped_result"></div>
<button id="crop_button">Crop</button>




<script type="text/javascript" defer>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#blah').attr('src', e.target.result)
            };
            reader.readAsDataURL(input.files[0]);
            setTimeout(initCropper, 1000);
        }
    }

    function initCropper() {
        var image = document.getElementById('blah');
        var cropper = new Cropper(image, {
            aspectRatio: 1 / 1,
            crop: function(e) {
                console.log(e.detail.x);
                console.log(e.detail.y);
            }
        });

        // On crop button clicked
        document.getElementById('crop_button').addEventListener('click', function() {
            var imgurl = cropper.getCroppedCanvas().toDataURL();
            var img = document.createElement("img");
            img.src = imgurl;
            document.getElementById("cropped_result").appendChild(img);

            /* ---------------- SEND IMAGE TO THE SERVER-------------------------*/

            cropper.getCroppedCanvas().toBlob(function(blob) {
                var formData = new FormData();
                formData.append('croppedImage', blob);
                // Use `jQuery.ajax` method
                $.ajax({
                    url: 'dl.php',
                    data: formData,
                    type: "POST",
                    cache: false,
                    contentType: false,
                    dataType: false,
                    success: function(data) {
                        location.reload();
                    },
                    error: function(e) {
                        alert("error while trying to add or update user!");
                    }
                });
            });
        })
    }
</script>