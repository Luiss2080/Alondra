import React from 'react';
import Calendar from '@/components/Calendar';
import { Calendar as CalendarIcon, Brain, Bell, Settings } from 'lucide-react';
import Link from 'next/link';

export default function Dashboard() {
  return (
    <div className="min-h-screen flex bg-background text-foreground">
      {/* Sidebar Layout */}
      <aside className="w-64 border-r border-glass-border bg-secondary/30 backdrop-blur-sm p-6 flex flex-col gap-8">
        <div className="text-2xl font-black bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
          Alondra Pro
        </div>
        
        <nav className="flex flex-col gap-2">
          <Link href="/dashboard" className="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl font-medium">
            <CalendarIcon size={20} />
            Calendario
          </Link>
          <button className="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl font-medium transition-colors text-foreground/70 hover:text-foreground">
            <Brain size={20} />
            IA Planificador
          </button>
          <button className="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl font-medium transition-colors text-foreground/70 hover:text-foreground">
            <Bell size={20} />
            Notificaciones
          </button>
          <button className="flex items-center gap-3 px-4 py-3 hover:bg-white/5 rounded-xl font-medium transition-colors text-foreground/70 hover:text-foreground">
            <Settings size={20} />
            Ajustes
          </button>
        </nav>
      </aside>

      {/* Main Content */}
      <main className="flex-1 p-8 overflow-y-auto">
        <div className="flex justify-between items-center mb-8">
          <h1 className="text-3xl font-bold">Panel de Control</h1>
          <div className="flex items-center gap-4">
            <span className="px-4 py-1.5 bg-accent/20 text-accent rounded-full text-sm font-semibold border border-accent/30">
              Pro Member
            </span>
            <div className="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-accent shadow-lg flex items-center justify-center text-white font-bold">
              U
            </div>
          </div>
        </div>

        {/* Calendar Widget (RF-1) */}
        <div className="h-[600px]">
          <Calendar />
        </div>
      </main>
    </div>
  );
}
