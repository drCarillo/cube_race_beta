$(document).ready(function() {
    $('#submit_command').click(function(event) {
        var command = $('#command').val();
        var cube_id = $('#cube_id').val();
        $.ajax({
            url: "./CubeRace/index.php",
      	    type: "post",
      	    dataType: 'json',
      	    data: {command: command, cube_id: cube_id},
      	    success: function(response) {
      	        $('#message').html(response.message);
      	        if (response.cube_id != null) {
      	            $('#cube_id').val(response.cube_id);
      	        }
      	        return false;
      	    },
      	    error:function(response, status) {
      	        $('#message').html('<br />Some kind of error happened.</br />See administrator.');
      	        return false;
      	    }
        });
        return false;
    });
});