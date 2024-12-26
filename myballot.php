<script>
document.addEventListener('DOMContentLoaded', function () {
    const previewButtons = document.querySelectorAll('.preview-btn');

    previewButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const sessionId = this.getAttribute('data-session-id');

            // Send AJAX request to fetch preview data
            fetch(`preview_logic.php?session_id=${sessionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        let message = `Election: ${data[0].election_title}\n\n`;
                        data.forEach(item => {
                            message += `Position: ${item.position_name}\nCandidate: ${item.candidate_name}\n\n`;
                        });
                        alert(message); // Show the list of candidates and positions
                    } else {
                        alert('No votes found for this election.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching preview data:', error);
                    alert('An error occurred while fetching the preview data.');
                });
        });
    });
});
</script>