@inject('actions', '\dimonka2\flatform\Actions\Action')

<script>
    class FlatformActions{
        processOutput(response){
            console.log(response);
            // alert(response.message);
            if(response.result == 'error') alert(response.message);

            if(response.redirect) {
                switch (response.redirect) {
                    case "{{$actions::reload}}":
                        location.reload();
                        break;
                    default:
                        window.location.href = response.redirect;
                        break;
                }
            }
        }

        runAction(actionName, params){
            // run final action
            let self = this;
            $.ajax({
                type: 'POST',
                url: '@route("flatform.action")',
                data: params,
                success: function(response){
                    self.processOutput(response);
                },
                error: function(html, status) {
                    self.processError(html, status);
                },
            });

        }

        processError(html, status){
            console.log(html.responseText);
            console.log(status);
            alert('Action was not run!');
        }

        showModal(actionName, response) {
            let modalID = "#{{$actions::modalID}}";
            $('#action-container').html(response.form);

            $(modalID).modal();
            if (typeof initBSPickers !== "undefined") initBSPickers();
            if (typeof initSelect2 !== "undefined") initSelect2();

            let self = this;
            $(modalID + ' .confirm').click(function(e){
                e.preventDefault();
                // collect form inputs
                let data = $(modalID + '_form').serializeArray();
                let params = {};
                data.forEach(element => {
                    params[element.name] = element.value;
                });
                self.runAction(actionName, data);
                $(modalID).modal('hide')
            })
        }

        run (actionName, params) {
            if(!params || typeof params === 'string' || params instanceof String) {
                params = {'params': params};
            };
            params._token = "{{ csrf_token() }}";
            params.name = actionName;
            params.dataType = 'JSON';
            let self = this;

            // get action form
            $.ajax({
                type: 'POST',
                url: '@route("flatform.action-fields")',
                data: params,
                success: function(response){
                    if (response.redirect === "{{$actions::noform}}") {
                        console.log(response);
                        return self.runAction(actionName, params);
                    }
                    return self.showModal(actionName, response);
                },
                error: function(html, status) {
                    self.processError(html, status);
                },
            });

        }
    }
    var {{config('flatform.actions.js-function', 'ffactions')}} = new FlatformActions();
</script>

<div id="action-container"></div>
