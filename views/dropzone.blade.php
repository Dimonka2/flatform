@push($context->getJsStack())
    <script>
        function dropzoneInit(selector) {
            let el = $(selector);
            let onSuccess       = el.attr('onSuccess');
            let onError         = el.attr('onError');
            let onInit          = el.attr('onInit');
            let onAddedfile     = el.attr('onAddedfile');
            let onRemovedfile   = el.attr('onRemovedfile');

            let dropzoneOptions = {
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                init: function () {
                    if(onInit) window[onInit](this);
                    if(onSuccess) {
                        this.on("success", function (file, resp) {
                            window[onSuccess](file, resp);
                        });
                    }
                    if(onError) {
                        this.on("error", function (file, resp) {
                            window[onError](file, resp);
                        });
                    }
                    if(onAddedfile) {
                        this.on("addedfile", function (file, resp) {
                            window[onAddedfile](file, selector);
                        });
                    }
                    if(onRemovedfile) {
                        this.on("removedfile", function (file) {
                            if (window[onRemovedfile](file, selector)) {
                                file.previewElement.remove();
                            }
                        });
                    }
                }
            };

            let newDropzone = new Dropzone(selector, dropzoneOptions);
            let files = el.find('input[name="files"]');
            if(files.length > 0) {
                // show existing files
                let fileList = JSON.parse(files.val());
                fileList.forEach(function(file){
                    newDropzone.emit("addedfile", file);
                    newDropzone.emit("complete", file);
                    newDropzone.files.push(file);
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

    </script>
@endpush
