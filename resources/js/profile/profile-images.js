let cropper;
const imageInput = document.getElementById('profile_image');
const bannerInput = document.getElementById('banner_image');
const cropperModal = document.getElementById('cropper-modal');
const cropperImage = document.getElementById('cropper-image');
const cropperClose = document.getElementById('cropper-close');
const cropperCrop = document.getElementById('cropper-crop');
const profilePreviewLarge = document.getElementById('profile-preview-large');
const profilePreviewMedium = document.getElementById('profile-preview-medium');
const profilePreviewSmall = document.getElementById('profile-preview-small');
const bannerPreview = document.getElementById('banner-preview');

// Handle profile image input change
imageInput.addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            // Display the selected image in the preview elements
            profilePreviewLarge.src = e.target.result;
            profilePreviewMedium.src = e.target.result;
            profilePreviewSmall.src = e.target.result;

            // Open the cropper modal for cropping
            cropperImage.src = e.target.result;
            cropperImage.dataset.type = 'profile';
            cropperModal.classList.remove('hidden');
            cropper = new Cropper(cropperImage, {
                aspectRatio: 1, // 1:1 for profile image
                viewMode: 1,
            });
        };
        reader.readAsDataURL(file);
    }
});

// Handle banner image input change
bannerInput.addEventListener('change', function (event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            // Display the selected image in the banner preview
            bannerPreview.src = e.target.result;

            // Open the cropper modal for cropping
            cropperImage.src = e.target.result;
            cropperImage.dataset.type = 'banner';
            cropperModal.classList.remove('hidden');
            cropper = new Cropper(cropperImage, {
                aspectRatio: 16 / 9, // 16:9 for banner image
                viewMode: 1,
            });
        };
        reader.readAsDataURL(file);
    }
});

// Handle cropper modal close
cropperClose.addEventListener('click', function () {
    if (cropper) cropper.destroy();
    cropperModal.classList.add('hidden');
});

// Handle crop action
cropperCrop.addEventListener('click', function () {
    if (cropperImage.dataset.type === 'profile' || cropperImage.dataset.type === 'banner') {
        const canvas = cropper.getCroppedCanvas();

        // Check if the file is a GIF
        const isGif = cropperImage.dataset.type === 'profile'
            ? imageInput.files[0].type === 'image/gif'
            : bannerInput.files[0].type === 'image/gif';

        if (isGif) {
            // If it's a GIF, skip cropping and compression
            const originalFile = cropperImage.dataset.type === 'profile'
                ? imageInput.files[0]
                : bannerInput.files[0];

            const url = URL.createObjectURL(originalFile);

            if (cropperImage.dataset.type === 'profile') {
                // Update profile image previews
                profilePreviewLarge.src = url;
                profilePreviewMedium.src = url;
                profilePreviewSmall.src = url;

                // Update the file input with the original GIF
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(originalFile);
                imageInput.files = dataTransfer.files;
            } else if (cropperImage.dataset.type === 'banner') {
                // Update banner preview
                bannerPreview.src = url;

                // Update the file input with the original GIF
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(originalFile);
                bannerInput.files = dataTransfer.files;
            }

            if (cropper) cropper.destroy();
            cropperModal.classList.add('hidden');
            return;
        }

        // For non-GIF files, proceed with cropping and compression
        canvas.toBlob(function (blob) {
            const originalFileType = cropperImage.dataset.type === 'profile'
                ? imageInput.files[0].type
                : bannerInput.files[0].type;

            const originalExtension = originalFileType.split('/')[1]; // e.g., 'jpeg', 'png'

            // Compress the cropped image
            new Compressor(blob, {
                quality: 0.8, // Adjust quality (0.1 to 1) as needed
                mimeType: originalFileType, // Use the original file type
                success(compressedBlob) {
                    const url = URL.createObjectURL(compressedBlob);

                    if (cropperImage.dataset.type === 'profile') {
                        // Update profile image previews
                        profilePreviewLarge.src = url;
                        profilePreviewMedium.src = url;
                        profilePreviewSmall.src = url;

                        // Update the file input with the compressed image
                        const fileInput = new File(
                            [compressedBlob],
                            `compressed-profile.${originalExtension}`,
                            { type: originalFileType }
                        );
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(fileInput);
                        imageInput.files = dataTransfer.files;
                    } else if (cropperImage.dataset.type === 'banner') {
                        // Update banner preview
                        bannerPreview.src = url;

                        // Update the file input with the compressed image
                        const fileInput = new File(
                            [compressedBlob],
                            `compressed-banner.${originalExtension}`,
                            { type: originalFileType }
                        );
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(fileInput);
                        bannerInput.files = dataTransfer.files;
                    }

                    if (cropper) cropper.destroy();
                    cropperModal.classList.add('hidden');
                },
                error(err) {
                    console.error('Compression error:', err);
                },
            });
        });
    }
});
