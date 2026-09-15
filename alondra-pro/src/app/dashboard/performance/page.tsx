"use client";

import React from 'react';
import { Activity, Target, TrendingUp, Award } from 'lucide-react';
import { motion } from 'framer-motion';

/**
 * Página de Rendimiento (Performance)
 * Muestra métricas visuales e interactivas sobre el desempeño académico del estudiante.
 */
export default function PerformancePage() {
  return (
    <div className="flex h-full flex-col relative overflow-hidden">
      {/* Fondo decorativo dinámico */}
      <div className="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-green-500/10 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>

      <header className="mb-8">
        <h1 className="text-3xl font-bold flex items-center gap-3">
          <Activity className="text-green-500" size={32} />
          Rendimiento Académico
        </h1>
        <p className="text-foreground/60 text-sm mt-1">Analiza tus horas de estudio, productividad y alcance de metas.</p>
      </header>

      {/* Contenedor principal de tarjetas estadísticas */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10 mb-8">
        {[
          { title: 'Horas Estudiadas', value: '18.5h', icon: Target, color: 'text-primary', delay: 0 },
          { title: 'Exámenes Aprobados', value: '4/4', icon: Award, color: 'text-yellow-400', delay: 0.1 },
          { title: 'Productividad', value: '92%', icon: TrendingUp, color: 'text-green-400', delay: 0.2 },
          { title: 'Tareas Completadas', value: '24', icon: Activity, color: 'text-accent', delay: 0.3 }
        ].map((stat, i) => (
          <motion.div 
            key={i}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: stat.delay }}
            whileHover={{ y: -5, scale: 1.02 }}
            className="glass-panel p-6 rounded-3xl border border-glass-border shadow-lg flex flex-col justify-between h-36"
          >
            <div className="flex justify-between items-start">
              <div className="text-foreground/60 text-sm font-semibold">{stat.title}</div>
              <div className={`p-2 rounded-xl bg-background/50 ${stat.color} shadow-inner`}>
                <stat.icon size={20} />
              </div>
            </div>
            <div className="text-3xl font-black">{stat.value}</div>
          </motion.div>
        ))}
      </div>

      {/* Gráfico Simulado de Progreso */}
      <motion.div 
        initial={{ opacity: 0, scale: 0.98 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ delay: 0.4 }}
        className="flex-1 glass-panel rounded-3xl border border-glass-border p-6 flex flex-col"
      >
        <h2 className="text-xl font-bold mb-4">Progreso Mensual (Octubre)</h2>
        <div className="flex-1 flex items-end justify-between gap-4 p-4 border-b border-glass-border/50">
          {/* Barras de progreso simuladas para demostrar una UI compleja */}
          {[40, 70, 45, 90, 65, 80, 100].map((height, i) => (
            <motion.div 
              key={i}
              initial={{ height: 0 }}
              animate={{ height: `${height}%` }}
              transition={{ delay: 0.5 + (i * 0.1), type: 'spring' }}
              className="w-full bg-gradient-to-t from-primary/50 to-accent rounded-t-lg relative group cursor-pointer"
            >
              <div className="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-background border border-glass-border px-2 py-1 rounded text-xs transition-opacity shadow-lg">
                {height}%
              </div>
            </motion.div>
          ))}
        </div>
        <div className="flex justify-between mt-4 text-xs font-semibold text-foreground/50">
          <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
        </div>
      </motion.div>
    </div>
  );
}
