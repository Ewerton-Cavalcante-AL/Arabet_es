import CriarPartida from '@/Components/CriarPartida';
import { useState, useEffect } from 'react';

// Sub-componente para gerenciar o estado individual de cada jogo
const PartidaAdminCard = ({ partida, onUpdateOdds, onFinalizar }) => {
    // Estados locais para os inputs de odds
    const [odds, setOdds] = useState({
        mandante: partida.odd_mandante,
        empate: partida.odd_empate,
        visitante: partida.odd_visitante
    });

    // Estados locais para os inputs de placar
    const [placar, setPlacar] = useState({
        mandante: 0,
        visitante: 0
    });

    return (
        <div className="bg-[#1E1E1E] p-6 rounded-lg border border-[#333] shadow-lg mb-4">
            <div className="flex justify-between items-center mb-4">
                <h3 className="text-white font-bold text-xl">
                    {partida.mandante} <span className="text-gray-400 font-normal mx-2">x</span> {partida.visitante}
                </h3>
                <span className={`px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider ${
                    partida.status === 'AGENDADA' ? 'bg-blue-500/20 text-blue-400' : 'bg-green-500/20 text-green-400'
                }`}>
                    {partida.status}
                </span>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                {/* SETOR 1: Gerenciamento de Odds */}
                <div className="bg-[#2A2A2A] p-4 rounded-md">
                    <h4 className="text-gray-400 text-sm font-medium mb-3 uppercase">Atualizar Odds</h4>
                    <div className="flex gap-2 mb-3">
                        <div className="flex-1">
                            <label className="text-xs text-gray-500 block mb-1">Casa (1)</label>
                            <input 
                                type="number" step="0.01" min="1.01"
                                className="w-full bg-[#121212] text-white border border-[#444] rounded p-2 focus:border-blue-500 outline-none"
                                value={odds.mandante}
                                onChange={(e) => setOdds({...odds, mandante: e.target.value})}
                            />
                        </div>
                        <div className="flex-1">
                            <label className="text-xs text-gray-500 block mb-1">Empate (X)</label>
                            <input 
                                type="number" step="0.01" min="1.01"
                                className="w-full bg-[#121212] text-white border border-[#444] rounded p-2 focus:border-blue-500 outline-none"
                                value={odds.empate}
                                onChange={(e) => setOdds({...odds, empate: e.target.value})}
                            />
                        </div>
                        <div className="flex-1">
                            <label className="text-xs text-gray-500 block mb-1">Fora (2)</label>
                            <input 
                                type="number" step="0.01" min="1.01"
                                className="w-full bg-[#121212] text-white border border-[#444] rounded p-2 focus:border-blue-500 outline-none"
                                value={odds.visitante}
                                onChange={(e) => setOdds({...odds, visitante: e.target.value})}
                            />
                        </div>
                    </div>
                    <button 
                        onClick={() => onUpdateOdds(partida.id_partida, odds)}
                        className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded transition-colors"
                    >
                        Salvar Novas Odds
                    </button>
                </div>

                {/* SETOR 2: Finalização de Jogo */}
                <div className="bg-[#2A2A2A] p-4 rounded-md border border-red-900/30">
                    <h4 className="text-red-400 text-sm font-medium mb-3 uppercase">Zona de Liquidação (Finalizar)</h4>
                    <div className="flex items-center gap-4 mb-3">
                        <div className="flex-1 text-center">
                            <label className="text-xs text-gray-400 block mb-1">{partida.mandante}</label>
                            <input 
                                type="number" min="0"
                                className="w-20 bg-[#121212] text-white text-center text-xl font-bold border border-[#444] rounded p-2 focus:border-red-500 outline-none mx-auto block"
                                value={placar.mandante}
                                onChange={(e) => setPlacar({...placar, mandante: e.target.value})}
                            />
                        </div>
                        <span className="text-gray-500 font-bold text-xl">X</span>
                        <div className="flex-1 text-center">
                            <label className="text-xs text-gray-400 block mb-1">{partida.visitante}</label>
                            <input 
                                type="number" min="0"
                                className="w-20 bg-[#121212] text-white text-center text-xl font-bold border border-[#444] rounded p-2 focus:border-red-500 outline-none mx-auto block"
                                value={placar.visitante}
                                onChange={(e) => setPlacar({...placar, visitante: e.target.value})}
                            />
                        </div>
                    </div>
                    <button 
                        onClick={() => {
                            if(window.confirm(`Tem certeza que deseja finalizar com placar ${placar.mandante} x ${placar.visitante}? Isso pagará os apostadores.`)) {
                                onFinalizar(partida.id_partida, placar);
                            }
                        }}
                        className="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 rounded transition-colors"
                    >
                        Finalizar Jogo e Pagar
                    </button>
                </div>

            </div>
            
        </div>
    );
};

// Componente Principal
export default function AdminPanel() {
    const [partidas, setPartidas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [feedback, setFeedback] = useState({ show: false, message: '', type: '' });

    // Pega a URL da API da sua variável de ambiente (ou coloque direto para teste)
    const API_URL = 'http://localhost:8000/api';

    // Busca os jogos ao montar o componente
    useEffect(() => {
        carregarPartidas();
    }, []);

    const carregarPartidas = async () => {
        try {
            // Reaproveitando a rota pública de jogos para listar no admin
            const response = await fetch(`${API_URL}/jogos`);
            const data = await response.json();
            setPartidas(data);
            setLoading(false);
        } catch (error) {
            mostrarFeedback('Erro ao carregar os jogos.', 'error');
            setLoading(false);
        }
    };

    const handleUpdateOdds = async (idPartida, novasOdds) => {
        try {
            const response = await fetch(`${API_URL}/admin/partidas/${idPartida}/odds`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    odd_mandante: novasOdds.mandante,
                    odd_empate: novasOdds.empate,
                    odd_visitante: novasOdds.visitante
                })
            });

            if (response.ok) {
                mostrarFeedback('Odds atualizadas com sucesso!', 'success');
                carregarPartidas(); // Recarrega a lista
            } else {
                mostrarFeedback('Erro ao atualizar odds.', 'error');
            }
        } catch (error) {
            mostrarFeedback('Erro de conexão.', 'error');
        }
    };

    const handleFinalizar = async (idPartida, placar) => {
        try {
            const response = await fetch(`${API_URL}/admin/partidas/${idPartida}/finalizar`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    placar_mandante: placar.mandante,
                    placar_visitante: placar.visitante
                })
            });

            if (response.ok) {
                const data = await response.json();
                mostrarFeedback(`Jogo finalizado! Vencedor: ${data.resultado_oficial}.`, 'success');
                carregarPartidas(); // Remove o jogo finalizado da tela
            } else {
                mostrarFeedback('Erro ao liquidar o jogo.', 'error');
            }
        } catch (error) {
            mostrarFeedback('Erro de conexão ao finalizar.', 'error');
        }
    };

    const mostrarFeedback = (message, type) => {
        setFeedback({ show: true, message, type });
        setTimeout(() => setFeedback({ show: false, message: '', type: '' }), 4000);
    };

    return (
        <div className="min-h-screen bg-[#121212] p-8 font-sans">
            <div className="max-w-5xl mx-auto">
                
                <header className="mb-8 border-b border-[#333] pb-4 flex justify-between items-end">
                    <div>
                        <h1 className="text-3xl font-bold text-white tracking-tight">Painel Operacional</h1>
                        <p className="text-gray-400 mt-1">Gerenciamento de Odds e Liquidação de Partidas</p>
                    </div>
                </header>

                {/* Alerta Flutuante de Feedback */}
                {feedback.show && (
                    <div className={`p-4 mb-6 rounded text-white font-medium ${feedback.type === 'error' ? 'bg-red-600' : 'bg-green-600'}`}>
                        {feedback.message}
                    </div>
                )}

                {loading ? (
                    <div className="text-center text-gray-400 py-10">Carregando partidas...</div>
                ) : (
                    <div>
                        {partidas.length === 0 ? (
                            <div className="text-center text-gray-500 py-10 bg-[#1E1E1E] rounded-lg border border-[#333]">
                                Nenhum jogo agendado no momento.
                            </div>
                        ) : (
                            partidas.map(partida => (
                                <PartidaAdminCard 
                                    key={partida.id_partida} 
                                    partida={partida} 
                                    onUpdateOdds={handleUpdateOdds}
                                    onFinalizar={handleFinalizar}
                                />
                            ))
                        )}
                    </div>
                )}
            </div>
            <CriarPartida />
        </div>
    );
}