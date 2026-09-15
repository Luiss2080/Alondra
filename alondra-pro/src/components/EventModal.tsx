"use client";

import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { X, Calendar as CalendarIcon, Clock, Type, Save } from 'lucide-react';

interface EventModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export default function EventModal({ isOpen, onClose }: EventModalProps) {
  return (
    <AnimatePresence>
      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
          <motion.div 
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="absolute inset-0 bg-background/80 backdrop-blur-sm"
            onClick={onClose}
          />
          <motion.div 
            initial={{ opacity: 0, scale: 0.9, y: 20 }}
            animate={{ opacity: 1, scale: 1, y: 0 }}
            exit={{ opacity: 0, scale: 0.9, y: 20 }}
            transition={{ type: "spring", damping: 25, stiffness: 300 }}
            className="relative w-full max-w-lg glass-panel rounded-2xl p-6 shadow-2xl z-10 border border-glass-border bg-secondary/80"
          >
            <button 
              onClick={onClose}
              className="absolute top-4 right-4 p-2 rounded-full hover:bg-white/10 transition-colors text-foreground/70 hover:text-foreground"
            >
              <X size={20} />
            </button>

            <h2 className="text-2xl font-bold mb-6 flex items-center gap-2">
              <CalendarIcon className="text-primary" />
              Nuevo Evento
            </h2>

            <form className="flex flex-col gap-5">
              <div>
                <label className="block text-sm font-medium text-foreground/80 mb-1">Título del Evento</label>
                <input 
                  type="text" 
                  placeholder="Ej. Examen Final de Física"
                  className="w-full bg-background/50 border border-glass-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-foreground/80 mb-1 flex items-center gap-1">
                    <CalendarIcon size={14} /> Fecha
                  </label>
                  <input 
                    type="date" 
                    className="w-full bg-background/50 border border-glass-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium text-foreground/80 mb-1 flex items-center gap-1">
                    <Clock size={14} /> Hora
                  </label>
                  <input 
                    type="time" 
                    className="w-full bg-background/50 border border-glass-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-foreground/80 mb-1 flex items-center gap-1">
                  <Type size={14} /> Tipo de Evento
                </label>
                <select className="w-full bg-background/50 border border-glass-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all appearance-none">
                  <option value="tarea">Tarea (Índigo)</option>
                  <option value="examen">Examen (Violeta)</option>
                  <option value="estudio">Bloque de Estudio IA (Verde)</option>
                </select>
              </div>

              <motion.button 
                whileHover={{ scale: 1.02 }}
                whileTap={{ scale: 0.98 }}
                type="button"
                onClick={onClose}
                className="mt-4 w-full bg-primary hover:bg-primary/90 text-primary-foreground font-semibold rounded-xl px-4 py-3 shadow-lg flex items-center justify-center gap-2 transition-colors"
              >
                <Save size={18} />
                Guardar Evento
              </motion.button>
            </form>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
}
