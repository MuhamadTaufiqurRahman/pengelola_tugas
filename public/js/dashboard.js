// Auto-submit form saat select berubah
document.getElementById('statusSelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});

document.getElementById('prioritySelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});
document.getElementById('departementSelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});
document.getElementById('sortSelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});

// Auto-submit filter saat select berubah
document.querySelectorAll('select[name="status"], select[name="deadline_filter"], select[name="sort"]').forEach(
    select => {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });


