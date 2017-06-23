/*!
 * Debian Maintainer Dashboard Library
 */
$(function() {
  $( "#email1" ).autocomplete({
    source: "/dmd-emails.cgi",
    select: function(event, ui) {
        $("#email1").val(ui.item.value);
        $("#searchForm").submit();
    },
    appendTo: '#autocompletecontainer1'
  });
  $( "#email2" ).autocomplete({
    source: "/dmd-emails.cgi",
    select: function(event, ui) {
        $("#email2").val(ui.item.value);
        $("#searchForm").submit();
    },
    appendTo: '#autocompletecontainer2'
  });
  $( "#email3" ).autocomplete({
    source: "/dmd-emails.cgi",
    select: function(event, ui) {
        $("#email3").val(ui.item.value);
        $("#searchForm").submit();
    },
    appendTo: '#autocompletecontainer3'
  });
});
$(document).ready(function() {
$("table").tablesorter({
   theme: 'default'
}); 
$("tbody.todos tr").each(function(index, elem) { if ($.cookie(elem.id) == '1') $(elem).hide(); });
});
function hide_todo(id) {
  $.cookie(id, '1', { expires: 3650 });
  $("tbody.todos tr#"+id).hide();
}

function reset_todos() {
$("tbody.todos tr").each(function(index, elem) {
 $.cookie(elem.id, null);
 $(elem).show();
});
}
