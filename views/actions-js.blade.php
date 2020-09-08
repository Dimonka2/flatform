@inject('actions', '\dimonka2\flatform\Actions\Action')

@if(Flatform::config('flatform.livewire.active'))
    @livewire('flatform.actions')

    <script>
        document.addEventListener("livewire:load", function(event) {
            let id = "flatform-action";
            window.livewire.hook('{{$context::isLivewireV1() ? 'afterDomUpdate': 'message.processed'}}', () => {
                if($("#" + id).length > 0) {
                    let formID = "#{{$actions::formID}}";
                    let form = $(formID + ":not(.form-shown)")
                    if(!form.length) return;
                    form.addClass('form-shown');
                    // console.log(form);
                    form.submit(function() {
                        $("#" + id).modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        // protect from resubmitting
                        let form = $(formID + ":not(.form-submited)")
                        if(!form.length) return;
                        form.addClass('form-submited');

                        let componentId = $('.action-form-class').attr('wire:id');
                        // console.log(componentId);
                        let component = window.livewire.find(componentId);
                        let form_data = form.serializeArray()
                        console.log('Calling submit', form_data);
                        component.call('formSubmit', form_data );

                        return false; // return false to cancel form action
                    });
                    $("#" + id).modal('show');
                }
            });
            // navigate to element using ID
            window.livewire.on('navigateTo', link => {
                let element = $(link);
                if(element.length) {
                    //console.log(element, element.offset().top, {!! Flatform::config('flatform.livewire.top_offset_script')!!} );
                    $([document.documentElement, document.body]).animate({
                        scrollTop: element.offset().top {!! Flatform::config('flatform.livewire.top_offset_script') !!}
                    }, 500);
                } else {
                    console.log('Element "' + link + '" is not found.');
                }
            })
        });
    </script>
@endif
