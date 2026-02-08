import React, { useState } from 'react';
import { AdminModule } from './components/AdminModule';
import { StudentModule } from './components/StudentModule';
import { GraduationCap, LayoutDashboard, UserPlus, ArrowLeft } from 'lucide-react';

export default function App() {
  const [view, setView] = useState<'home' | 'admin' | 'student'>('home');

  return (
    <div className="min-h-screen bg-gray-100 flex flex-col font-sans">
      {/* Background with overlay */}
      <div 
        className="fixed inset-0 z-0 opacity-10 pointer-events-none"
        style={{
          backgroundImage: `url('https://images.unsplash.com/photo-1605450103158-0f7973a73677?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtb2Rlcm4lMjBhYnN0cmFjdCUyMGdlb21ldHJpYyUyMHdoaXRlJTIwZ3JheSUyMGJhY2tncm91bmR8ZW58MXx8fHwxNzY5NDkxNTYwfDA&ixlib=rb-4.1.0&q=80&w=1080')`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        }}
      />

      {/* Header */}
      <header className="bg-white shadow-md z-10 relative">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
          <div 
            className="flex items-center gap-2 cursor-pointer" 
            onClick={() => setView('home')}
          >
            <div className="bg-blue-600 p-2 rounded-lg text-white">
              <GraduationCap size={24} />
            </div>
            <h1 className="text-xl font-bold text-gray-800 tracking-tight">EduMaster <span className="text-blue-600">Pro</span></h1>
          </div>
          
          {view !== 'home' && (
            <button 
              onClick={() => setView('home')}
              className="text-gray-500 hover:text-blue-600 flex items-center gap-1 text-sm font-medium transition-colors"
            >
              <ArrowLeft size={16} /> Back to Home
            </button>
          )}
        </div>
      </header>

      {/* Main Content */}
      <main className="flex-grow z-10 relative py-8 px-4 sm:px-6 lg:px-8">
        {view === 'home' && (
          <div className="max-w-4xl mx-auto mt-10 animate-in fade-in zoom-in-95 duration-500">
            <div className="text-center mb-12">
              <h2 className="text-4xl font-extrabold text-gray-900 mb-4">Welcome to the Course Portal</h2>
              <p className="text-xl text-gray-600 max-w-2xl mx-auto">
                Manage your institute's courses effectively or register as a student to start your learning journey.
              </p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              {/* Admin Card */}
              <div 
                onClick={() => setView('admin')}
                className="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer group border border-gray-100 transform hover:-translate-y-1"
              >
                <div className="h-32 bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center group-hover:from-blue-600 group-hover:to-cyan-600 transition-all">
                  <LayoutDashboard className="text-white w-16 h-16 opacity-80 group-hover:scale-110 transition-transform duration-300" />
                </div>
                <div className="p-8 text-center">
                  <h3 className="text-2xl font-bold text-gray-800 mb-2">Admin Module</h3>
                  <p className="text-gray-500 mb-6">Create courses, manage schedules, and view registered students.</p>
                  <span className="inline-block px-6 py-2 bg-blue-50 text-blue-700 font-semibold rounded-full group-hover:bg-blue-100 transition-colors">
                    Access Dashboard
                  </span>
                </div>
              </div>

              {/* Student Card */}
              <div 
                onClick={() => setView('student')}
                className="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 cursor-pointer group border border-gray-100 transform hover:-translate-y-1"
              >
                <div className="h-32 bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center group-hover:from-indigo-600 group-hover:to-purple-600 transition-all">
                  <UserPlus className="text-white w-16 h-16 opacity-80 group-hover:scale-110 transition-transform duration-300" />
                </div>
                <div className="p-8 text-center">
                  <h3 className="text-2xl font-bold text-gray-800 mb-2">Student Registration</h3>
                  <p className="text-gray-500 mb-6">Browse available courses, check fees, and register online instantly.</p>
                  <span className="inline-block px-6 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-full group-hover:bg-indigo-100 transition-colors">
                    Register Now
                  </span>
                </div>
              </div>
            </div>
          </div>
        )}

        {view === 'admin' && <AdminModule />}
        {view === 'student' && <StudentModule />}
      </main>

      {/* Footer */}
      <footer className="bg-white border-t border-gray-200 mt-auto py-6 z-10 relative">
        <div className="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
          <p>© {new Date().getFullYear()} EduMaster Pro. All rights reserved.</p>
          <p className="mt-1">Powered by React, Tailwind CSS & Supabase</p>
        </div>
      </footer>
    </div>
  );
}
