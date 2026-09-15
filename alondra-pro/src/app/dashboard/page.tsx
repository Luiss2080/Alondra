"use client";

import React, { useState } from 'react';
import Calendar from '@/components/Calendar';
import EventModal from '@/components/EventModal';
import { Calendar as CalendarIcon, Brain, Bell, Settings, Plus, LogOut, Search, Activity, Zap, Star } from 'lucide-react';
import Link from 'next/link';
import { motion } from 'framer-motion';

export default function Dashboard() {
  const [isModalOpen, setIsModalOpen] = useState(false);

  return (
    <div className="min-h-screen flex bg-background text-foreground overflow-hidden">
      {/* Decorative background meshes */}
      <div className="absolute top-[-20%] right-[-10%] w-[50%] h-[50%] bg-primary/20 rounded-full mix-blend-screen filter blur-[120px] pointer-events-none"></div>
      <div className="absolute bottom-[-10%] left-[-10%] w-[40%] h-[40%] bg-accent/20 rounded-full mix-blend-screen filter blur-[100px] pointer-events-none"></div>

      {/* Sidebar Layout */}
      <aside className="w-72 border-r border-glass-border bg-secondary/20 backdrop-blur-md p-6 flex flex-col gap-8 justify-between relative z-10 shadow-2xl">
        <div>
          <motion.div 
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            className="flex items-center gap-3 mb-10 px-2 cursor-pointer group"
          >
            <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-primary to-accent flex items-center justify-center text-white font-bold shadow-lg group-hover:scale-110 transition-transform">
              <svg width="20" height="20" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M 30 70 L 50 30 L 70 70" stroke="white" strokeWidth="12" strokeLinecap="round" strokeLinejoin="round" />
                <circle cx="50" cy="55" r="5" fill="white" />
              </svg>
            </div>
            <div className="text-2xl font-black bg-gradient-to-r from-white to-foreground/80 bg-clip-text text-transparent tracking-tight">
              Alondra Pro
            </div>
          </motion.div>
          
          <nav className="flex flex-col gap-3">
            {[
              { icon: CalendarIcon, label: 'Calendario', active: true, href: '/dashboard' },
              { icon: Brain, label: 'IA Planificador', active: false, href: '/dashboard/ai-planner' },
              { icon: Activity, label: 'Rendimiento', active: false, href: '/dashboard/performance' },
              { icon: Bell, label: 'Notificaciones', active: false, href: '/dashboard/notifications' },
              { icon: Settings, label: 'Ajustes', active: false, href: '/dashboard/settings' }
            ].map((item, i) => (
              <Link 
                key={i}
                href={item.href}
              >
                <motion.div
                  whileHover={{ scale: 1.02, x: 5 }}
                  whileTap={{ scale: 0.98 }}
                  className={`flex items-center w-full gap-4 px-5 py-3.5 rounded-2xl font-medium transition-all duration-300 ${
                    item.active 
                    ? 'bg-primary/10 text-primary border border-primary/20 shadow-inner' 
                    : 'hover:bg-white/5 text-foreground/60 hover:text-foreground border border-transparent hover:border-white/5'
                  }`}
                >
                  <item.icon size={22} className={item.active ? 'text-primary' : 'text-foreground/50'} />
                  {item.label}
                </motion.div>
              </Link>
            ))}
          </nav>
        </div>

        <motion.div 
          whileHover={{ scale: 1.02 }}
          className="p-5 rounded-2xl bg-gradient-to-br from-accent/10 to-primary/10 border border-glass-border mb-4 text-left"
        >
          <div className="flex items-center gap-2 mb-2">
            <Star className="text-yellow-400" size={16} fill="currentColor" />
            <span className="font-bold text-sm">Plan Premium</span>
          </div>
          <p className="text-xs text-foreground/70 mb-3">Tienes acceso ilimitado a las sugerencias de la IA.</p>
          <button className="text-xs font-semibold text-primary hover:underline">Ver detalles</button>
        </motion.div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-8 overflow-y-auto relative z-10 flex flex-col gap-8">
        
        {/* Header */}
        <header className="flex justify-between items-center bg-secondary/10 p-4 rounded-3xl border border-glass-border backdrop-blur-md">
          <div className="flex items-center bg-background/50 border border-glass-border rounded-full px-4 py-2 w-96 transition-all focus-within:ring-2 focus-within:ring-primary/50 focus-within:w-[400px]">
            <Search size={18} className="text-foreground/40 mr-2" />
            <input 
              type="text" 
              placeholder="Buscar eventos, exámenes..." 
              className="bg-transparent border-none focus:outline-none text-sm w-full"
            />
          </div>

          <div className="flex items-center gap-6">
            <motion.button 
              whileHover={{ scale: 1.05, boxShadow: "0 0 20px rgba(99, 102, 241, 0.4)" }}
              whileTap={{ scale: 0.95 }}
              onClick={() => setIsModalOpen(true)}
              className="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-primary to-accent hover:opacity-90 text-white font-bold rounded-full shadow-lg transition-all"
            >
              <Plus size={18} />
              Nuevo Evento
            </motion.button>

            <div className="flex items-center gap-3 border-l border-glass-border pl-6 cursor-pointer group">
              <div className="text-right hidden sm:block">
                <div className="text-sm font-bold">Estudiante</div>
                <div className="text-xs text-foreground/50">Online</div>
              </div>
              <div className="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent shadow-lg flex items-center justify-center text-white font-bold group-hover:ring-4 ring-primary/30 transition-all">
                E
              </div>
            </div>
          </div>
        </header>

        {/* Stats Row */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {[
            { title: 'Próximo Examen', value: 'Física', icon: Zap, color: 'text-accent' },
            { title: 'Progreso Semanal', value: '75%', icon: Activity, color: 'text-green-400' },
            { title: 'Tareas Pendientes', value: '4', icon: CalendarIcon, color: 'text-primary' }
          ].map((stat, i) => (
            <motion.div 
              key={i}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: i * 0.1 }}
              whileHover={{ y: -5 }}
              className="bg-secondary/20 border border-glass-border p-6 rounded-3xl backdrop-blur-sm shadow-xl flex items-center gap-5 cursor-default"
            >
              <div className={`p-4 rounded-2xl bg-background/50 ${stat.color} shadow-inner`}>
                <stat.icon size={24} />
              </div>
              <div>
                <div className="text-foreground/50 text-sm font-medium mb-1">{stat.title}</div>
                <div className="text-2xl font-black">{stat.value}</div>
              </div>
            </motion.div>
          ))}
        </div>

        {/* Calendar Widget (RF-1) */}
        <motion.div 
          initial={{ opacity: 0, scale: 0.98 }}
          animate={{ opacity: 1, scale: 1 }}
          transition={{ delay: 0.3 }}
          className="flex-1 min-h-[600px] flex flex-col"
        >
          <Calendar />
        </motion.div>

      </main>

      <EventModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} />
    </div>
  );
}
