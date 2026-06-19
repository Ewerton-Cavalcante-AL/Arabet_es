import React, { useState, useEffect } from 'react';

export default function CriarPartida() {
    // Estado para armazenar a lista de times vinda do banco
    const [times, setTimes] = useState([]);
    const [loadingTimes, setLoadingTimes] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [feedback, setFeedback] = useState({ show: false, message: '', type: '' });

    // Estado do formulário refletindo exatamente o que sua API Laravel pede
    const [formData, setFormData] = useState({
        id_mandante: '',
        id_visitante: '',
        data_hora: '',
        odd_mandante: '1.01',
        odd_empate: '1.01',
        odd_visitante: '1.01'
    });

    const API_URL = 'http://localhost:8000/api';

    // Carrega os times cadastrados para listar nos Selects
    useEffect(() => {
        const carregarTimes = async () => {
            try {
                // Supondo que você crie essa rota básica para listar os times cadastrados
                const response = await fetch(`${API_URL}/admin/times`); 
                if (response.ok) {
                    const data = await response.json();
                    setTimes(data);
                } else {
                    mostrarFeedback('Erro ao carregar lista de times.', 'error');
                }
            } catch (error) {
                mostrarFeedback('Erro de conexão ao buscar times.', 'error');
            } finally {
                setLoadingTimes(false);
            }
        };

        carregarTimes();
    }, []);

    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: value
        }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();

        // Validação front-end: impede confronto do time contra ele mesmo (Constraint do seu SQL)
        if (formData.id_mandante === formData.id_visitante) {
            mostrarFeedback('O time mandante não pode ser igual ao time visitante.', 'error');
            return;
        }

        setSubmitting(true);

        try {
            const response = await fetch(`${API_URL}/admin/partidas`, { // ajuste a URL se necessário
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                mostrarFeedback('Partida agendada com sucesso!', 'success');
                // Reseta o formulário
                setFormData({
                    id_mandante: '',
                    id_visitante: '',
                    data_hora: '',
                    odd_mandante: '1.01',
                    odd_empate: '1.01',
                    odd_visitante: '1.01'
                });
            } else {
                // Captura mensagens de erro de validação do Laravel
                const erroMsg = data.message || 'Erro ao criar partida.';
                mostrarFeedback(erroMsg, 'error');
            }
        } catch (error) {
            mostrarFeedback('Erro de conexão com o servidor.', 'error');
        } finally {
            setSubmitting(false);
        }
    };

    const mostrarFeedback = (message, type) => {
        setFeedback({ show: true, message, type });
        setTimeout(() => setFeedback({ show: false, message: '', type: '' }), 4000);
    };

    return (
        <div className="min-h-screen bg-[#121212] p-8 font-sans text-white">
            <div className="max-w-2xl mx-auto bg-[#1E1E1E] p-8 rounded-lg border border-[#333] shadow-xl">
                
                <header className="mb-6 border-b border-[#333] pb-4">
                    <h1 className="text-2xl font-bold tracking-tight">Cadastrar Nova Partida</h1>
                    <p className="text-gray-400 text-sm mt-1">Insira os times, a data do confronto e defina as odds iniciais.</p>
                </header>

                {/* Feedback de Sucesso ou Erro */}
                {feedback.show && (
                    <div className={`p-4 mb-6 rounded text-white font-medium text-sm transition-all ${
                        feedback.type === 'error' ? 'bg-red-600/90 border border-red-500' : 'bg-green-600/90 border border-green-500'
                    }`}>
                        {feedback.message}
                    </div>
                )}

                <form onSubmit={handleSubmit} className="space-y-6">
                    
                    {/* SELEÇÃO DE TIMES */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label className="text-sm font-medium text-gray-400 block mb-2">Time Mandante (Casa)</label>
                            <select
                                name="id_mandante"
                                required
                                value={formData.id_mandante}
                                onChange={handleChange}
                                disabled={loadingTimes}
                                className="w-full bg-[#121212] text-white border border-[#444] rounded p-3 focus:border-blue-500 outline-none disabled:opacity-50"
                            >
                                <option value="">{loadingTimes ? 'Carregando times...' : 'Selecione o time'}</option>
                                {times.map(time => (
                                    <option key={time.id_time} value={time.id_time}>{time.nome} ({time.cidade})</option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="text-sm font-medium text-gray-400 block mb-2">Time Visitante (Fora)</label>
                            <select
                                name="id_visitante"
                                required
                                value={formData.id_visitante}
                                onChange={handleChange}
                                disabled={loadingTimes}
                                className="w-full bg-[#121212] text-white border border-[#444] rounded p-3 focus:border-blue-500 outline-none disabled:opacity-50"
                            >
                                <option value="">{loadingTimes ? 'Carregando times...' : 'Selecione o time'}</option>
                                {times.map(time => (
                                    <option key={time.id_time} value={time.id_time}>{time.nome} ({time.cidade})</option>
                                ))}
                            </select>
                        </div>
                    </div>

                    {/* DATA E HORA */}
                    <div>
                        <label className="text-sm font-medium text-gray-400 block mb-2">Data e Hora do Jogo</label>
                        <input
                            type="datetime-local"
                            name="data_hora"
                            required
                            value={formData.data_hora}
                            onChange={handleChange}
                            className="w-full bg-[#121212] text-white border border-[#444] rounded p-3 focus:border-blue-500 outline-none"
                        />
                    </div>

                    {/* DEFINIÇÃO DE ODDS INICIAIS */}
                    <div className="bg-[#2A2A2A] p-4 rounded-md border border-[#333]">
                        <h3 className="text-gray-300 text-sm font-semibold uppercase tracking-wider mb-4">Definição das Odds (Cotações)</h3>
                        
                        <div className="grid grid-cols-3 gap-3">
                            <div>
                                <label className="text-xs text-gray-400 block mb-1">Vitória Casa (1)</label>
                                <input
                                    type="number" step="0.01" min="1.01" name="odd_mandante" required
                                    value={formData.odd_mandante}
                                    onChange={handleChange}
                                    className="w-full bg-[#121212] text-white border border-[#444] rounded p-2 focus:border-blue-500 outline-none text-center font-semibold"
                                />
                            </div>
                            <div>
                                <label className="text-xs text-gray-400 block mb-1">Empate (X)</label>
                                <input
                                    type="number" step="0.01" min="1.01" name="odd_empate" required
                                    value={formData.odd_empate}
                                    onChange={handleChange}
                                    className="w-full bg-[#121212] text-white border border-[#444] rounded p-2 focus:border-blue-500 outline-none text-center font-semibold"
                                />
                            </div>
                            <div>
                                <label className="text-xs text-gray-400 block mb-1">Vitória Fora (2)</label>
                                <input
                                    type="number" step="0.01" min="1.01" name="odd_visitante" required
                                    value={formData.odd_visitante}
                                    onChange={handleChange}
                                    className="w-full bg-[#121212] text-white border border-[#444] rounded p-2 focus:border-blue-500 outline-none text-center font-semibold"
                                />
                            </div>
                        </div>
                    </div>

                    {/* BOTÃO SUBMIT */}
                    <button
                        type="submit"
                        disabled={submitting || loadingTimes}
                        className="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-800 text-white font-bold py-3 px-4 rounded transition-colors text-center block outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        {submitting ? 'Agendando Partida...' : 'Criar e Publicar Jogo'}
                    </button>

                </form>
            </div>
        </div>
    );
}