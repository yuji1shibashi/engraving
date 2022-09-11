var id = $('.select').val();
	if (!id) {
		var id = $('.userid').val();
	}

	var time = new Date();
	var month = (time.getMonth() + 1);
	var year = time.getFullYear();
	$('.month').text(month + "月");
	$('.month').attr({'value':"{{old(" + month + ")}}"});
	$('.getmonth').attr({'value':month});

	// 検索した値を保存
	$(document).ready(function() {
		month = $('#month').val()
		var set_id = $('#set_id').val()
		$('.month').text(month + "月");
		$('.getmonth').attr({'value':month});
		$("select").val(set_id);
	});

	$('.select').change(function(){
		id = $('.select').val()
	});

	// 月選択
	$('.monthdown').on('click' , function(){
		if (month > 1) {
			$('#tbody').empty();
			month--;
			$('.month').text(month + "月");
			$('.getmonth').attr({'value':month});
		}
		search();
	});

	// 月選択
	$('.monthup').on('click' , function(){
		if (month < 12) {
			month++;
			$('.month').text(month + "月");
			$('.getmonth').attr({'value':month});
		}
		search();
	});

	// 検索ボタン
	$('#serch').on('click' , function(){
		search();
	});

	function search()
	{
		var id = $('[name="user"]').val();
		var month = $('.getmonth').val();
		document.location = "/punch_list/" + id + "/" + year + "/" + month;
	}