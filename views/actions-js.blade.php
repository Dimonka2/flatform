<script>
    class FlatformActions{
        processOutput(msg){
            console.log(msg);
            alert('wow' + msg);
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
            let self = this;
            $.ajax({
                type: 'POST',
                url: '@route("flatform.action")',
                data: params,
                success: function(msg){
                    self.processOutput(msg);
                },
                error: function(html, status) {
                    self.processError(html, status);
                },
            });
        }
    }
    var ffactions = new FlatformActions();
</script>
