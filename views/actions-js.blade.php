@inject('actions', '\dimonka2\flatform\Actions\Action')

@if(Flatform::config('flatform.livewire.active'))
    @livewire('flatform.actions')

    <script>
        document.addEventListener("livewire:load", function(event) {
            let id = "flatform-action";
            window.livewire.hook('afterDomUpdate', () => {
                if($("#" + id).length > 0) {
                    let formID = "#{{$actions::formID}}";
                    let form = $(formID)
                    form.submit(function() {
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
        });
    </script>
@endif
