"use client";

import React from 'react';
import { Brain, Sparkles, Send } from 'lucide-react';
import { motion } from 'framer-motion';

export default function AIPlanner() {
  return (
    <div className="flex h-[80vh] flex-col overflow-hidden relative">
      <div className="absolute top-[-20%] right-[-10%] w-[50%] h-[50%] bg-accent/20 rounded-full mix-blend-screen filter blur-[120px] pointer-events-none"></div>

      <header className="mb-6">
        <h1 className="text-3xl font-bold flex items-center gap-3">
          <Brain className="text-accent" size={32} />
          IA Planificador
        </h1>
        <p className="text-foreground/60 text-sm mt-1">Chatea con tu asistente para estructurar tu semana de estudio.</p>
      </header>

      <div className="flex-1 glass-panel rounded-3xl p-6 flex flex-col justify-between border border-glass-border">
        <div className="flex-1 overflow-y-auto flex flex-col gap-4 p-2">
          {/* AI Message */}
          <motion.div 
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            className="flex items-start gap-4"
          >
            <div className="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-white shadow-lg shrink-0">
              <Sparkles size={18} />
            </div>
            <div className="bg-secondary/40 p-4 rounded-2xl rounded-tl-none border border-glass-border text-sm max-w-[80%]">
              ¡Hola! Veo que tienes un <strong>Examen de Física</strong> la próxima semana. Te sugiero dividir el estudio en 3 bloques de 2 horas. ¿Quieres que los agregue al calendario?
            </div>
          </motion.div>

          {/* User Message */}
          <motion.div 
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ delay: 0.5 }}
            className="flex items-start gap-4 justify-end mt-4"
          >
            <div className="bg-primary p-4 rounded-2xl rounded-tr-none text-white text-sm shadow-md max-w-[80%]">
              Sí, agrégalos pero solo por las tardes después de las 4 PM.
            </div>
            <div className="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white shadow-lg shrink-0 font-bold">
              U
            </div>
          </motion.div>
        </div>

        {/* Input Area */}
        <div className="mt-6 flex items-center gap-3">
          <input 
            type="text" 
            placeholder="Pídele a la IA que reorganice tus tareas..." 
            className="flex-1 bg-background/50 border border-glass-border rounded-full px-6 py-4 text-foreground focus:outline-none focus:ring-2 focus:ring-accent/50 transition-all shadow-inner"
          />
          <motion.button 
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            className="w-14 h-14 rounded-full bg-accent hover:bg-accent/90 flex items-center justify-center text-white shadow-lg transition-colors"
          >
            <Send size={20} className="ml-1" />
          </motion.button>
        </div>
      </div>
    </div>
  );
}
