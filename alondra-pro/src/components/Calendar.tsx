"use client";

import React, { useState, useEffect } from 'react';
import { format, startOfWeek, addDays, isSameDay } from 'date-fns';
import { es } from 'date-fns/locale';

interface EventType {
  id: string;
  title: string;
  date: string | Date;
  type: 'tarea' | 'examen';
  userId: string;
}

interface ApiEvent extends Omit<EventType, 'date'> {
  startDate: string;
}

export default function Calendar() {
  const [events, setEvents] = useState<EventType[]>([]);
  const startDate = startOfWeek(new Date(), { weekStartsOn: 1 });
  
  const weekDays = Array.from({ length: 7 }).map((_, i) => addDays(startDate, i));

  useEffect(() => {
    fetch('/api/events')
      .then(res => res.json())
      .then(data => {
        // Ensure dates are parsed correctly using startDate
        const parsedEvents = (data as ApiEvent[]).map((e) => ({
          ...e,
          date: new Date(e.startDate)
        }));
        setEvents(parsedEvents);
      })
      .catch(err => console.error("Error fetching events:", err));
  }, []);

  const handleDragStart = (e: React.DragEvent, eventId: string) => {
    e.dataTransfer.setData('eventId', eventId);
  };

  const handleDragOver = (e: React.DragEvent) => {
    e.preventDefault(); // Necessary to allow dropping
  };

  const handleDrop = async (e: React.DragEvent, targetDate: Date) => {
    e.preventDefault();
    const eventId = e.dataTransfer.getData('eventId');
    
    // Optimistic UI update
    setEvents(prev => prev.map(ev => 
      ev.id === eventId ? { ...ev, date: targetDate } : ev
    ));
    
    try {
      await fetch('/api/events', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          id: eventId,
          date: targetDate,
        }),
      });
      console.log(`Evento ${eventId} actualizado en BD al ${format(targetDate, 'yyyy-MM-dd')}`);
    } catch (err) {
      console.error("Error actualizando evento:", err);
      // Opcional: Revertir en caso de error
    }
  };

  return (
    <div className="w-full h-full bg-secondary/20 rounded-2xl border border-glass-border p-6 shadow-xl backdrop-blur-md">
      <div className="flex justify-between items-center mb-6">
        <h2 className="text-2xl font-bold bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
          Mi Calendario
        </h2>
        <div className="text-foreground/70">{format(new Date(), 'MMMM yyyy', { locale: es })}</div>
      </div>
      
      <div className="grid grid-cols-7 gap-4">
        {weekDays.map((day, i) => (
          <div key={i} className="text-center font-semibold text-foreground/60 mb-2">
            {format(day, 'EEEE', { locale: es })}
          </div>
        ))}
        
        {weekDays.map((day, i) => (
          <div 
            key={i} 
            className="min-h-[120px] bg-background/50 border border-glass-border rounded-xl p-2 transition-colors hover:bg-background/80"
            onDragOver={handleDragOver}
            onDrop={(e) => handleDrop(e, day)}
          >
            <div className="text-right text-sm text-foreground/50 mb-2">
              {format(day, 'd')}
            </div>
            
            {events.filter(e => isSameDay(new Date(e.date), day)).map(ev => (
              <div
                key={ev.id}
                draggable
                onDragStart={(e) => handleDragStart(e, ev.id)}
                className={`p-2 mb-2 rounded-lg text-sm cursor-grab active:cursor-grabbing text-white shadow-md transition-transform hover:scale-105 ${
                  ev.type === 'examen' ? 'bg-accent' : 'bg-primary'
                }`}
              >
                {ev.title}
              </div>
            ))}
          </div>
        ))}
      </div>
    </div>
  );
}
