"use client";

import React, { useState } from 'react';
import Calendar from '@/components/Calendar';
import EventModal from '@/components/EventModal';
import { Calendar as CalendarIcon, Brain, Bell, Settings, Plus, LogOut } from 'lucide-react';
import Link from 'next/link';
import { motion } from 'framer-motion';

export default function Dashboard() {
  const [isModalOpen, setIsModalOpen] = useState(false);

  return (
    <div className="min-h-screen flex bg-background text-foreground">
      {/* Sidebar Layout */}
      <aside className="w-64 border-r border-glass-border bg-secondary/30 backdrop-blur-sm p-6 flex flex-col gap-8 justify-between">
        <div>
          <div className="text-2xl font-black bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent mb-8 px-2">
            Alondra Pro
          </div>
          
          <nav className="flex flex-col gap-2">
            <Link href="/dashboard" className="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium transition-colors hover:bg-primary/20">
              <CalendarIcon size={20} />
              Calendario
            </Link>
            <button className="flex items-center w-full gap-3 px-4 py-3 hover:bg-white/5 rounded-xl font-medium transition-colors text-foreground/70 hover:text-foreground">
              <Brain size={20} />
              IA Planificador
            </button>
            <button className="flex items-center w-full gap-3 px-4 py-3 hover:bg-white/5 rounded-xl font-medium transition-colors text-foreground/70 hover:text-foreground">
              <Bell size={20} />
              Notificaciones
            </button>
            <button className="flex items-center w-full gap-3 px-4 py-3 hover:bg-white/5 rounded-xl font-medium transition-colors text-foreground/70 hover:text-foreground">
              <Settings size={20} />
              Ajustes
            </button>
          </nav>
        </div>

        <button className="flex items-center w-full gap-3 px-4 py-3 hover:bg-red-500/10 rounded-xl font-medium transition-colors text-red-400 hover:text-red-300">
          <LogOut size={20} />
          Cerrar Sesión
        </button>
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-8 overflow-y-auto relative">
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold mb-1">Panel de Control</h1>
            <p className="text-foreground/60 text-sm">Gestiona tus eventos escolares y preparaciones.</p>
          </div>
          <div className="flex items-center gap-6">
            <motion.button 
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              onClick={() => setIsModalOpen(true)}
              className="flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground font-semibold rounded-full shadow-lg transition-colors"
            >
              <Plus size={18} />
              Nuevo Evento
            </motion.button>

            <div className="flex items-center gap-3 border-l border-glass-border pl-6">
              <span className="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs font-semibold border border-accent/30 uppercase tracking-wider">
                Pro
              </span>
              <div className="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent shadow-lg flex items-center justify-center text-white font-bold cursor-pointer hover:shadow-primary/50 transition-shadow">
                U
              </div>
            </div>
          </div>
        </div>

        {/* Calendar Widget (RF-1) */}
        <div className="h-[700px]">
          <Calendar />
        </div>
      </main>

      <EventModal isOpen={isModalOpen} onClose={() => setIsModalOpen(false)} />
    </div>
  );
}
