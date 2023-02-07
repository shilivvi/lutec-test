window.addEventListener('DOMContentLoaded', (event) => {
    const $likes = document.querySelectorAll('.likes');

    if( $likes.length === 0 ){
        return;
    }

    $likes.forEach(item => {
        const plusBtn = item.querySelector('.likes__btn--plus');
        const minusBtn = item.querySelector('.likes__btn--minus');
        const postId = item.getAttribute('data-id');

        if( !postId ){
            return;
        }

        plusBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            likeHandler(1, postId);
        })

        minusBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            likeHandler(-1, postId);
        })
    });
});

async function likeHandler(val, postId){
    const ajaxUrl = lutec.ajaxUrl;
    const nonce = lutec.nonce;

    const data = new FormData();
    data.append('post_id', postId);
    data.append('value', val);
    data.append('action', 'lutec_likes');
    data.append('nonce', nonce);

    fetch(ajaxUrl, {
        method: 'POST',
        body: data
    })
        .then((response) => response.json())
        .then((likes) => {
            const $wrap = document.querySelector(`[data-id="${postId}"] .likes__count`);
            $wrap.textContent = likes;

            $wrap.classList.remove('likes__count--bad');
            $wrap.classList.remove('likes__count--good');

            if(likes >= 0){
                $wrap.classList.add('likes__count--good')
            }else{
                $wrap.classList.add('likes__count--bad')
            }
        })



}