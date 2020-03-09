<script>
    class FlatformActions{
        processOutput(response){
            console.log(response);
            // alert(response.message);
            if(response.result == 'error') alert(response.message);

            if(response.redirect) {
                switch (response.redirect) {
                    case 'reload':
                        location.reload();
                        break;
                    case 'back':
                        window.history.back();
                        break;
                    default:
                        window.location.href = response.redirect;
                        break;
                }
            }
        }

        processError(html, status){
            console.log(html.responseText);
            console.log(status);
        }

        run (actionName, params) {
            if(!params || typeof params === 'string' || params instanceof String) {
                params = {'params': params};
            };
            params._token = "{{ csrf_token() }}";
            params.name = actionName;
            params.dataType = 'JSON';
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
    }
    var {{config('flatform.actions.js-function', 'ffactions')}} = new FlatformActions();
</script>
