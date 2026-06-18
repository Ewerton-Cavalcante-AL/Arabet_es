export default function FooterComponent() {
  return (
    <div>
        <footer className="bg-[#0D0D0D] text-gray-400 py-8 mt-40 rounded-2xl">
            <div className="container mx-auto px-4">
                <div className="flex flex-col md:flex-row justify-between items-center">
                    <div className="mb-4 md:mb-0">
                        <img src="images/logo.png" alt="Logo da AraBet" className="w-30 h-10"/>
                    </div>
                    <div className="flex flex-col md:flex-row gap-4">
                        <a href="#" className="hover:text-[#7DFF00] transition">Jogos</a>
                        <a href="#" className="hover:text-[#7DFF00] transition">Ao vivo</a>
                        <a href="#" className="hover:text-[#7DFF00] transition">Tabelas</a>
                        <a href="#" className="hover:text-[#7DFF00] transition">VIP</a>
                        <a href="#" className="hover:text-[#7DFF00] transition">Suporte</a>
                    </div>
                </div>
                <div className="mt-6 text-center text-sm">
                    <p>&copy; {new Date().getFullYear()} AraBet. Todos os direitos reservados.</p>
                    <p className="mt-2">CNPJ: 00.000.000/0001-00 | Endereço: Rua Exemplo, 123, Cidade, Estado</p>
                </div>
            </div>
        </footer>
    </div>
  )
}
