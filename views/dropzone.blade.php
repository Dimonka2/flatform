@push($context->getJsStack())
    <script>
        function dropzoneInit(selector) {
            let el = $(selector);
            let onSuccess       = el.attr('onSuccess');
            let onError         = el.attr('onError');
            let onRemovedfile   = el.attr('onRemovedfile');
            let onInit          = el.attr('onInit');
            let dropzoneOptions = {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                addRemoveLinks: !!onRemovedfile,
                init: function () {

                    if(onSuccess) {
                        this.on("success", function (file, resp) {
                            window[onSuccess](file, resp, el);
                        });
                    }
                    if(onError) {
                        this.on("error", function (message) {
                            window[onError](message, el);
                        });
                    }
                    if(onRemovedfile) {
                        this.on("removedfile", function (file) {
                            window[onRemovedfile](file, el);
                            file.previewElement.remove();
                        });
                    }
                }
            };
            if(onInit) window[onInit](dropzoneOptions);
            let newDropzone = new Dropzone(selector, dropzoneOptions);
            let files = el.find('input[name="files"]');
            if(files) {
                // show existing files
                let fileList = JSON.parse(files.val());
                fileList.forEach(function(file){
                    newDropzone.options.addedfile.call(newDropzone, file);
                });
            }
        }

        function initDropzones() {
            $('.dropzone:not(.dropzone-enabled)').each(function(i, obj) {
                $(obj).addClass('dropzone-enabled');
                dropzoneInit(obj);
            });
        }

        $(document).ready(function () {
            initDropzones();
        })

        function removeFile(file) {
            console.log(file);
        }
    </script>
@endpush
