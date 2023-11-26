<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<input type="text" id="date-range">

<script>
  $(function() {
    $("#date-range").datepicker({
        numberOfMonths: 2,
        onSelect: function(selectedDate) {
            var option = this.id === "date-range" ? "minDate" : "maxDate",
            instance = $(this).data("datepicker"),
            date = $.datepicker.parseDate(
                instance.settings.dateFormat ||
                $.datepicker._defaults.dateFormat,
                selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
  });
</script>

</body>
</html>
