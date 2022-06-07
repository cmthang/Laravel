$(document).ready(function() {
    $('#filter-id').select2({
        ajax: {
            url: '/ajax/userSearch',
            data: function (params) {
                var query = {
                    keyword: params.term,
                    type: 'public'
                };

                // Query parameters will be ?search=[term]&type=public
                return query;
            }
        }
    });
});