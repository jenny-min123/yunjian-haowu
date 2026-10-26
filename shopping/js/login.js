document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.onclick = () => {
        document.querySelectorAll('.form').forEach(f => f.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(x => x.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.target).classList.add('active');
    }
})

document.getElementById('role').onchange = function(){
    document.getElementById('shop_info').style.display = this.value=='seller' ? 'block' : 'none';
}

window.onload = function(){
    const hash = location.hash.replace('#','');
    if(hash){
        document.querySelectorAll('.form').forEach(f=>f.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(x=>x.classList.remove('active'));
        document.querySelector(`[data-target="${hash}"]`).classList.add('active');
        document.getElementById(hash).classList.add('active');
    }
}