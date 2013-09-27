<?php
/**
 * Шаблон создания переменной
 * @author Ganjar@ukr.net
 */
?>

<form id="create-var-form" action="<?php echo SLIAdmin::getUrl($_GET['action']);?>" method="POST">
    <table class="table table-striped items filters create" id="create">
    	<thead>
            <tr>
                <td>Оригинал</td>
                <td>Значения</td>
            </tr>        
    	</thead>
    	<tbody>
    		<tr class="grey">
    			<td class="create-original">
                    <textarea class="form-control" name="create[original]" placeholder="Оригинал"></textarea>
                </td>
    			<td class="create-translate">
                    <?php foreach ($languages as $langKey=>$langValue) {?>
                        <textarea class="form-control" name="create[translate][<?php echo $langValue['alias'];?>]" placeholder="<?php echo $langValue['title'];?>"></textarea>
                    <?php }?>
                </td>
    		</tr>
    		<tr>
	    		<td colspan="2">
	            	<button class="btn btn-primary"><span class="save">Создать</span></button>
	            </td>
    		</tr>
        </tbody>
    </table>
</form>

<script type="text/javascript">
	$('#create-var-form').submit(function(){
		validateElement = $('[name="create[original]"]');
		if (validateElement.val()=='') {
			alert('Заполните поле "'+validateElement.attr('placeholder')+'"');
			validateElement.focus();
			return false;
		}
	});
    var createBtn = $('#create');
    createBtn.find('.create-original textarea').css('height', createBtn.find('.create-original').height());
	$('input[placeholder], textarea[placeholder]').autoResize({
			animate: false,
			limit: 9999999
	});
</script>