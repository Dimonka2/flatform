@inject('actions', '\dimonka2\flatform\Actions\Action')

@if(Flatform::config('flatform.livewire.active'))
    @livewire('flatform.actions')

    <script>
        document.addEventListener("livewire:load", function(event) {
            let id = "flatform-action";
            window.livewire.hook('afterDomUpdate', () => {
                if($("#" + id).length > 0) {
                    let formID = "#{{$actions::formID}}";
                    let form = $(formID + ":not(.form-shown)")
                    if(!form.length) return;
                    form.addClass('form-shown');
                    console.log(form);
                    form.submit(function() {
                        console.log('submit', event);
                        $("#" + id).modal('hide');
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        let componentId = $('.action-form-class').attr('wire:id');
                        // console.log(componentId);
                        let component = window.livewire.find(componentId);
                        component.call('formSubmit', form.serializeArray() );

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
