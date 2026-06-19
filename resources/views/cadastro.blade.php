<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - AraBet</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0D0D0D]">
    <section class="min-h-screen bg-[#0D0D0D] flex items-center justify-center p-6">
    
    <div class="fixed top-0 left-0 h-full w-full overflow-hidden -z-10">
        <div class="absolute -top-20 -right-20 h-96 w-96 rounded-full bg-lime-500/10 blur-[150px]"></div>
    </div>

    <div class="w-full max-w-lg rounded-3xl border border-[#202020] bg-[#111111] p-8 shadow-[0_0_40px_rgba(0,0,0,.5)]">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-white">Criar Conta</h2>
            <p class="text-gray-400 mt-2">Junte-se à maior comunidade de apostas</p>
        </div>

        <form action="{{ route('cadastro.store') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label class="block text-sm text-gray-400 mb-2">Nome Completo</label>
        <input type="text" name="name" required class="w-full rounded-2xl border border-[#202020] bg-[#171717] px-5 py-4 text-white focus:border-lime-400 outline-none transition" placeholder="Seu nome">
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-2">CPF</label>
        <input type="text" name="cpf" required class="w-full rounded-2xl border border-[#202020] bg-[#171717] px-5 py-4 text-white focus:border-lime-400 outline-none transition" placeholder="000.000.000-00">
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-2">E-mail</label>
        <input type="email" name="email" required class="w-full rounded-2xl border border-[#202020] bg-[#171717] px-5 py-4 text-white focus:border-lime-400 outline-none transition" placeholder="email@exemplo.com">
    </div>

    <div>
        <label class="block text-sm text-gray-400 mb-2">Senha</label>
        <input type="password" name="password" required class="w-full rounded-2xl border border-[#202020] bg-[#171717] px-5 py-4 text-white focus:border-lime-400 outline-none transition" placeholder="••••••••">
    </div>

    <input type="hidden" name="role" id="role" value="cliente">

    <div class="grid grid-cols-2 gap-4">
    <button type="button" id="btn-cliente" onclick="selectRole('cliente')" 
        class="btn-role rounded-2xl border border-lime-400 bg-lime-400 py-3 text-black font-semibold transition">
        Cliente
    </button>
    <button type="button" id="btn-admin" onclick="selectRole('admin')" 
        class="btn-role rounded-2xl border border-[#202020] bg-[#171717] py-3 text-white font-semibold hover:border-lime-400 transition">
        Administrador
    </button>
</div>

<input type="hidden" name="role" id="role" value="cliente">

    <button type="submit" class="w-full rounded-2xl bg-lime-400 py-4 font-bold text-black transition hover:bg-lime-300 mt-4">
        Cadastrar-se
    </button>

    <a href="/" class="block text-center text-gray-500 hover:text-white transition text-sm mt-4">
        Voltar para a página inicial
    </a>
</form>

    </div>
</section>

<script>
function selectRole(role) {

    document.getElementById('role').value = role;

    const buttons = document.querySelectorAll('.btn-role');
    

    buttons.forEach(btn => {
        btn.classList.remove('bg-lime-400', 'text-black', 'border-lime-400');
        btn.classList.add('bg-[#171717]', 'text-white', 'border-[#202020]');
    });


    const selectedBtn = document.getElementById('btn-' + role);
    selectedBtn.classList.remove('bg-[#171717]', 'text-white', 'border-[#202020]');
    selectedBtn.classList.add('bg-lime-400', 'text-black', 'border-lime-400');
}
</script>

</body>
</html>
