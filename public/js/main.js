const tasks = document.getElementById('tasks');

if (tasks) {
  console.log('1');
  tasks.addEventListener('click', e => {
    console.log('non');
    if (e.target.className === 'btn btn-danger delete-task') {
      console.log('oui');
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');
        fetch(`/task/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}