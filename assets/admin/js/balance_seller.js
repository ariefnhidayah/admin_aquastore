var datatable_data = {};

$(function () {
	const table = $("#table").DataTable({
		stateSave: $("#table").data("state-save")
			? $("#table").data("state-save")
			: true,
		processing: true,
		serverSide: true,
		paging: true,
		ordering: true,
		info: false,
		searching: true,
		destroy: true,
		responsive: true,
		ajax: {
			url: $("#table").attr("data-url"),
			type: "post",
			data: function (d) {
				d.additional_data = datatable_data;
				d.filter = get_form_data($("#filter-form"));
				return d;
			},
		},
		autoWidth: false,
		columnDefs: [
			{
				orderable: false,
				targets: "no-sort",
			},
			{
				className: "text-center",
				targets: "text-center",
			},
			{
				className: "text-right",
				targets: "text-right",
			},
		],
		order: $("th.default-sort").length
			? [[$("th.default-sort").index(), $("th.default-sort").attr("data-sort")]]
			: false,
		dom: '<"datatable-header"fBl><"datatable-scroll"t><"datatable-footer"ip>',
	});

	$("#filter").on("click", function () {
		table.ajax.reload();
	});

	$("#filter-by").on("change", function () {
		var a = $(this).val();
		var now = new Date();
		$("input[name=from]").datepicker("destroy");
		$("input[name=to]").datepicker("destroy");
		$("input[name=from]").attr("readonly", "readonly");
		$("input[name=to]").attr("readonly", "readonly");
		if (a == "today") {
			//hari ini
			$("input[name=from]").val(formatDate(now));
			$("input[name=to]").val(formatDate(now));
		} else if (a == "yesterday") {
			//kemarin
			var date = new Date();
			date.setDate(now.getDate() - 1);
			$("input[name=from]").val(formatDate(date));
			$("input[name=to]").val(formatDate(date));
		} else if (a == "this month") {
			//bulan ini
			var date1 = new Date(now.getFullYear(), now.getMonth(), 1);
			var date2 = new Date(now.getFullYear(), now.getMonth() + 1, 0);
			$("input[name=from]").val(formatDate(date1));
			$("input[name=to]").val(formatDate(date2));
		} else if (a == "last month") {
			//bulan lalu
			var date1 = new Date(now.getFullYear(), now.getMonth() - 1, 1);
			var date2 = new Date(now.getFullYear(), now.getMonth(), 0);
			$("input[name=from]").val(formatDate(date1));
			$("input[name=to]").val(formatDate(date2));
		} else if (a == "this year") {
			//tahun ini
			var date1 = new Date(now.getFullYear(), 0, 1);
			var date2 = new Date(now.getFullYear(), 12, 0);
			$("input[name=from]").val(formatDate(date1));
			$("input[name=to]").val(formatDate(date2));
		} else if (a == 99) {
			$("input[name=from]").datepicker({ dateFormat: "yy-mm-dd" });
			$("input[name=from]").removeAttr("readonly");
			$("input[name=to]").datepicker({ dateFormat: "yy-mm-dd" });
			$("input[name=to]").removeAttr("readonly");
		}
	});

	$("#export").on("click", function () {
		form_data = get_form_data($("#filter-form"));
		window.open(
			site_url +
				"/report/export?from=" +
				form_data.from +
				"&to=" +
				form_data.to,
			"_blank"
		);
	});
});

function formatDate(date) {
	var d = new Date(date),
		month = "" + (d.getMonth() + 1),
		day = "" + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2) month = "0" + month;
	if (day.length < 2) day = "0" + day;

	return [year, month, day].join("-");
}

function get_form_data($form) {
	var unindexed_array = $form.serializeArray();
	var indexed_array = {};

	$.map(unindexed_array, function (n, i) {
		indexed_array[n["name"]] = n["value"];
	});

	return indexed_array;
}

function openModal(id, e) {
	$("#acceptModal").modal("show");
	const data = $(e).data();
	$("#image").val(null);
	$("#confirm_image").val("");
	$("#form_accepted input[name=id]").val(id);
	$("#form_accepted span#bank_name").text(data.bank);
	$("#form_accepted span#account_number").text(data.account_number);
	$("#form_accepted span#account_holder").text(data.account_holder);
}

function changeImage(event) {
	const file = event.target.files[0];
	// encode the file using the FileReader API
	const reader = new FileReader();
	reader.onloadend = () => {
		const base64String = reader.result.replace("data:", "").replace(/^.+,/, "");
		$("#confirm_image").val(base64String);
		let html = '<img class="img-thumbnail" src="' + reader.result + '" />';
		html +=
			'<button class="btn btn-danger btn-sm mt-3" onclick="deleteImage()">Hapus</button>';
		$("#image_cover").html(html);
	};
	reader.readAsDataURL(file);
}

function deleteImage() {
	let html =
		'<input type="file" name="image" accept="image/*" id="image" required onchange="changeImage(event)">';
	$("#image_cover").html(html);
	$("#confirm_image").val("");
}

function accept(event, $this) {
	event.preventDefault();
	const url = $($this).attr("action");
	const method = $($this).attr("method");
	if ($("#confirm_image").val() == "") {
		Swal.fire({
			title: "Something wen't wrong!",
			type: "error",
			text: "Please input image!",
		});
	} else {
		$.ajax({
			url,
			method,
			data: {
				image: $("#confirm_image").val(),
				id: $("#form_accepted input[name=id]").val(),
			},
			success: function (data) {
				data = JSON.parse(data);
				if (data.status == "success") {
					Swal.fire({
						title: "Notice!",
						type: "success",
						text: data.message,
					}).then((result) => {
						window.location.reload();
					});
				} else {
					Swal.fire({
						title: "Warning!",
						type: "error",
						text: data.message,
					});
				}
			},
		});
	}
}

function rejectBalance($this, e) {
    const url = $($this).attr('href')
    e.preventDefault()
	Swal.fire({
		title: "Apakah ingin ditolak?",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Iya, Tolak!",
		cancelButtonText: "Tidak!",
	}).then((result) => {
		if (result.value) {
			$.ajax({
				url: url,
				success: function (data) {
					data = JSON.parse(data);
					Swal.fire("Berhasil!", data.message, "success");
                    window.location.reload();
				},
			});
		}
	});
}
