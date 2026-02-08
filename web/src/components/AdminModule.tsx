import React, { useState, useEffect } from 'react';
import { api } from '../utils/api';
import { Calendar, Plus, List, IndianRupee, Clock, FileText, AlertCircle, CheckCircle, Users } from 'lucide-react';

interface Course {
  id: string;
  title: string;
  description: string;
  start_date: string;
  end_date: string;
  duration: string;
  amount: number;
}

interface Registration {
  id: string;
  student_name: string;
  email: string;
  mobile: string;
  course_title: string;
  created_at: string;
}

export function AdminModule() {
  const [activeTab, setActiveTab] = useState<'create' | 'view' | 'registrations'>('create');
  const [courses, setCourses] = useState<Course[]>([]);
  const [registrations, setRegistrations] = useState<Registration[]>([]);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

  const [formData, setFormData] = useState({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    duration: '',
    amount: '',
  });

  useEffect(() => {
    if (activeTab === 'view') {
      fetchCourses();
    } else if (activeTab === 'registrations') {
      fetchRegistrations();
    }
  }, [activeTab]);

  const fetchCourses = async () => {
    setLoading(true);
    try {
      const data = await api.getCourses();
      // Sort client-side
      const sorted = (data || []).sort((a: any, b: any) => 
        new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
      );
      setCourses(sorted);
    } catch (error: any) {
      console.error('Error fetching courses:', error);
      setMessage({ type: 'error', text: 'Failed to load courses: ' + error.message });
    }
    setLoading(false);
  };

  const fetchRegistrations = async () => {
    setLoading(true);
    try {
      const data = await api.getRegistrations();
      // Sort client-side
      const sorted = (data || []).sort((a: any, b: any) => 
        new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
      );
      setRegistrations(sorted);
    } catch (error: any) {
      console.error('Error fetching registrations:', error);
      setMessage({ type: 'error', text: 'Failed to load registrations: ' + error.message });
    }
    setLoading(false);
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setMessage(null);

    // Validation
    if (!formData.title || !formData.description || !formData.start_date || !formData.end_date || !formData.amount || !formData.duration) {
      setMessage({ type: 'error', text: 'All fields are required.' });
      return;
    }

    if (new Date(formData.start_date) > new Date(formData.end_date)) {
      setMessage({ type: 'error', text: 'Start date must be before End date.' });
      return;
    }

    setLoading(true);
    try {
      await api.createCourse({
        title: formData.title,
        description: formData.description,
        start_date: formData.start_date,
        end_date: formData.end_date,
        duration: formData.duration,
        amount: parseFloat(formData.amount),
      });

      setMessage({ type: 'success', text: 'Course created successfully!' });
      setFormData({
        title: '',
        description: '',
        start_date: '',
        end_date: '',
        duration: '',
        amount: '',
      });
    } catch (error: any) {
      setMessage({ type: 'error', text: error.message });
    }
    setLoading(false);
  };

  return (
    <div className="max-w-4xl mx-auto p-4 md:p-6">
      {/* Navigation Tabs */}
      <div className="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mb-6">
        <button
          onClick={() => setActiveTab('create')}
          className={`flex-1 py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm ${
            activeTab === 'create'
              ? 'bg-blue-600 text-white font-semibold'
              : 'bg-white text-gray-600 hover:bg-gray-50'
          }`}
        >
          <Plus size={20} />
          Create Course
        </button>
        <button
          onClick={() => setActiveTab('view')}
          className={`flex-1 py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm ${
            activeTab === 'view'
              ? 'bg-blue-600 text-white font-semibold'
              : 'bg-white text-gray-600 hover:bg-gray-50'
          }`}
        >
          <List size={20} />
          View Courses
        </button>
        <button
          onClick={() => setActiveTab('registrations')}
          className={`flex-1 py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-all shadow-sm ${
            activeTab === 'registrations'
              ? 'bg-blue-600 text-white font-semibold'
              : 'bg-white text-gray-600 hover:bg-gray-50'
          }`}
        >
          <Users size={20} />
          View Students
        </button>
      </div>

      {/* Content Area */}
      <div className="bg-white rounded-xl shadow-lg p-6 md:p-8 border border-gray-100">
        {activeTab === 'create' && (
          <div className="animate-in fade-in slide-in-from-bottom-4 duration-300">
            <h2 className="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
              <FileText className="text-blue-600" />
              Register New Course
            </h2>

            {message && (
              <div className={`p-4 rounded-lg mb-6 flex items-center gap-3 ${
                message.type === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'
              }`}>
                {message.type === 'success' ? <CheckCircle size={20} /> : <AlertCircle size={20} />}
                {message.text}
              </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-6">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Course Title</label>
                <input
                  type="text"
                  name="title"
                  value={formData.title}
                  onChange={handleInputChange}
                  className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                  placeholder="e.g. Full Stack Web Development"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea
                  name="description"
                  value={formData.description}
                  onChange={handleInputChange}
                  rows={4}
                  className="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none resize-none"
                  placeholder="Enter course details..."
                />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                  <div className="relative">
                    <Calendar className="absolute left-3 top-3.5 text-gray-400" size={18} />
                    <input
                      type="date"
                      name="start_date"
                      value={formData.start_date}
                      onChange={handleInputChange}
                      className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                    />
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                  <div className="relative">
                    <Calendar className="absolute left-3 top-3.5 text-gray-400" size={18} />
                    <input
                      type="date"
                      name="end_date"
                      value={formData.end_date}
                      onChange={handleInputChange}
                      className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                    />
                  </div>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                  <div className="relative">
                    <Clock className="absolute left-3 top-3.5 text-gray-400" size={18} />
                    <input
                      type="text"
                      name="duration"
                      value={formData.duration}
                      onChange={handleInputChange}
                      className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                      placeholder="e.g. 3 Months"
                    />
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-2">Course Amount (₹)</label>
                  <div className="relative">
                    <IndianRupee className="absolute left-3 top-3.5 text-gray-400" size={18} />
                    <input
                      type="number"
                      name="amount"
                      value={formData.amount}
                      onChange={handleInputChange}
                      className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all outline-none"
                      placeholder="e.g. 499.00"
                      min="0"
                      step="0.01"
                    />
                  </div>
                </div>
              </div>

              <button
                type="submit"
                disabled={loading}
                className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
              >
                {loading ? 'Processing...' : 'Register Course'}
              </button>
            </form>
          </div>
        )}

        {activeTab === 'view' && (
          <div className="animate-in fade-in slide-in-from-bottom-4 duration-300">
            <h2 className="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
              <List className="text-blue-600" />
              Existing Courses
            </h2>
            
            {loading ? (
              <div className="text-center py-10 text-gray-500">Loading courses...</div>
            ) : courses.length === 0 ? (
              <div className="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500">
                No courses found. Create one to get started.
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="border-b border-gray-200 bg-gray-50">
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Title</th>
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Duration</th>
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Fees</th>
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Dates</th>
                    </tr>
                  </thead>
                  <tbody>
                    {courses.map((course) => (
                      <tr key={course.id} className="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td className="py-3 px-4">
                          <div className="font-medium text-gray-900">{course.title}</div>
                          <div className="text-xs text-gray-500 truncate max-w-[200px]">{course.description}</div>
                        </td>
                        <td className="py-3 px-4 text-sm text-gray-600">{course.duration}</td>
                        <td className="py-3 px-4 font-medium text-green-600">₹{course.amount}</td>
                        <td className="py-3 px-4 text-sm text-gray-600">
                          {course.start_date} <br/> <span className="text-xs text-gray-400">to</span> {course.end_date}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}

        {activeTab === 'registrations' && (
          <div className="animate-in fade-in slide-in-from-bottom-4 duration-300">
            <h2 className="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
              <Users className="text-blue-600" />
              Student Registrations
            </h2>
            
            {loading ? (
              <div className="text-center py-10 text-gray-500">Loading registrations...</div>
            ) : registrations.length === 0 ? (
              <div className="text-center py-10 bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500">
                No students registered yet.
              </div>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full text-left border-collapse">
                  <thead>
                    <tr className="border-b border-gray-200 bg-gray-50">
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Student</th>
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Contact</th>
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Course</th>
                      <th className="py-3 px-4 font-semibold text-gray-700 text-sm">Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    {registrations.map((reg) => (
                      <tr key={reg.id} className="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td className="py-3 px-4">
                          <div className="font-medium text-gray-900">{reg.student_name}</div>
                        </td>
                        <td className="py-3 px-4 text-sm text-gray-600">
                          <div>{reg.email}</div>
                          <div className="text-xs text-gray-400">{reg.mobile}</div>
                        </td>
                        <td className="py-3 px-4 text-sm font-medium text-blue-600">
                           {reg.course_title}
                        </td>
                        <td className="py-3 px-4 text-sm text-gray-600">
                           {new Date(reg.created_at).toLocaleDateString()}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
