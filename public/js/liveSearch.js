// $(document).ready(function() {
//     var searchRequest = null;
//     $("#search").keyup(function() {
//         var minlength = 1;
//         var that = this;
//         var value = $(this).val();
//         var entitySelector = $("#result").html('');
//         if (value.length >= minlength ) {
//             if (searchRequest != null)
//                 searchRequest.abort();
//             searchRequest = $.ajax({
//                 type: "GET",
//                 url: '/search',
//                 data: {
//                     'q' : value
//                 },
//                 dataType: "email",
//                 success: function(msg){
//                     //we need to check if the value is the same
//                     if (value==$(that).val()) {
//                         var result = JSON.parse(msg);
//                         $.each(result, function(key, arr) {
//                             $.each(arr, function(id, value) {
//                                 if (key == 'equipes') {
//                                     if (id != 'error') {
//                                         console.log(value)
//                                         entitySelector.append('<h5>'+value[l]+'</li>');
//                                     } else {
//                                         entitySelector.append('<h5 class="errorLi">'+value+'</h5>');
//                                     }
//                                 }
//                             });
//                         });
//                     }
//                  }
//             });
//         }
//     });
// });