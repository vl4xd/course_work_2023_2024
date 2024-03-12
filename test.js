

var jsonTypes = <?php echo $jsonTypes; ?>;
var types = JSON.parse(jsonTypes);
$('.columns').append("<div data-role='fieldcontain'>"); 
$('.columns').append("<select name="select-choice-mini" id="select-choice-mini" data-mini="true" data-inline="true">");

            $('.columns').append("</select>");
            $('.columns').append("<a class="ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all" data-inline='true'>No text</a>");
            $('.columns').append("</div>");