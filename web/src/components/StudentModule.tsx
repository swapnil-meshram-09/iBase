import React, { useState, useEffect } from 'react';
import QRCode from 'react-qr-code';
import { api } from '../utils/api';
import { User, Mail, Phone, MapPin, BookOpen, Clock, IndianRupee, CheckCircle, ArrowRight } from 'lucide-react';

interface Course {
  id: string;
  title: string;
  duration: string;
  amount: number;
}

export function StudentModule() {
  const [courses, setCourses] = useState<Course[]>([]);
  const [loading, setLoading] = useState(false);
  const [submitting, setSubmitting] = useState(false);
  const [successData, setSuccessData] = useState<any>(null);

  const [formData, setFormData] = useState({
    name: '',
    email: '',
    mobile: '',
    address: '',
    courseId: '',
  });

  const [selectedCourse, setSelectedCourse] = useState<Course | null>(null);

  useEffect(() => {
    fetchCourses();
  }, []);

  const fetchCourses = async () => {
    setLoading(true);
    try {
      const data = await api.getCourses();
      if (data) {
        setCourses(data);
      }
    } catch (error) {
      console.error('Error fetching courses:', error);
    }
    setLoading(false);
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));

    if (name === 'courseId') {
      const course = courses.find(c => c.id.toString() === value);
      setSelectedCourse(course || null);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedCourse) return;

    setSubmitting(true);
    
    try {
      await api.createRegistration({
        student_name: formData.name,
        email: formData.email,
        mobile: formData.mobile,
        address: formData.address,
        course_id: formData.courseId,
        course_title: selectedCourse.title,
        amount_paid: selectedCourse.amount
      });

      // Prepare success data
      setSuccessData({
        studentName: formData.name,
        courseName: selectedCourse.title,
        amount: selectedCourse.amount,
        mobile: formData.mobile
      });
    } catch (error: any) {
      alert('Registration failed: ' + error.message);
    }
    setSubmitting(false);
  };

  if (successData) {
    // Generate WhatsApp Link
    const message = `Hello, I have registered for the course: ${successData.courseName}.\nName: ${successData.studentName}\nAmount: ₹${successData.amount}\nPlease confirm my registration.`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    
    // UPI String (Demo)
    const upiString = `upi://pay?pa=demo@upi&pn=Institute&am=${successData.amount}&cu=INR`;

    return (
      <div className="max-w-md mx-auto p-6 bg-white rounded-xl shadow-2xl animate-in zoom-in-95 duration-300 border border-green-100">
        <div className="text-center mb-6">
          <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 text-green-600">
            <CheckCircle size={32} />
          </div>
          <h2 className="text-2xl font-bold text-gray-800">Registration Successful!</h2>
          <p className="text-gray-600 mt-2">Please complete the payment to confirm your seat.</p>
        </div>

        <div className="bg-gray-50 p-6 rounded-xl border border-gray-200 mb-6 flex flex-col items-center">
          <div className="bg-white p-3 rounded-lg shadow-sm mb-4">
             <QRCode value={upiString} size={160} />
          </div>
          <p className="font-semibold text-lg text-gray-800">Amount: ₹{successData.amount}</p>
          <p className="text-sm text-gray-500 mt-1">Scan to Pay via UPI App</p>
        </div>

        <div className="space-y-3">
          <a 
            href={whatsappUrl} 
            target="_blank" 
            rel="noopener noreferrer"
            className="block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg text-center shadow-md transition-colors flex items-center justify-center gap-2"
          >
            <Phone size={20} />
            Send Confirmation via WhatsApp
          </a>
          
          <button 
            onClick={() => {
              setSuccessData(null);
              setFormData({ name: '', email: '', mobile: '', address: '', courseId: '' });
              setSelectedCourse(null);
            }}
            className="block w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-lg text-center transition-colors"
          >
            Register Another Student
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
      <div className="bg-gradient-to-r from-blue-600 to-indigo-700 p-6 text-white">
        <h2 className="text-2xl font-bold flex items-center gap-2">
          <BookOpen className="text-blue-100" />
          Student Course Registration
        </h2>
        <p className="text-blue-100 mt-1">Join our professional courses today.</p>
      </div>

      <div className="p-6 md:p-8">
        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Student Name</label>
              <div className="relative">
                <User className="absolute left-3 top-3.5 text-gray-400" size={18} />
                <input
                  type="text"
                  name="name"
                  required
                  value={formData.name}
                  onChange={handleInputChange}
                  className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                  placeholder="John Doe"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
              <div className="relative">
                <Mail className="absolute left-3 top-3.5 text-gray-400" size={18} />
                <input
                  type="email"
                  name="email"
                  required
                  value={formData.email}
                  onChange={handleInputChange}
                  className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                  placeholder="john@example.com"
                />
              </div>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">Mobile (WhatsApp)</label>
              <div className="relative">
                <Phone className="absolute left-3 top-3.5 text-gray-400" size={18} />
                <input
                  type="tel"
                  name="mobile"
                  required
                  value={formData.mobile}
                  onChange={handleInputChange}
                  className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                  placeholder="+1 234 567 890"
                />
              </div>
            </div>

            <div>
               <label className="block text-sm font-medium text-gray-700 mb-2">Address</label>
               <div className="relative">
                <MapPin className="absolute left-3 top-3.5 text-gray-400" size={18} />
                <input
                  type="text"
                  name="address"
                  required
                  value={formData.address}
                  onChange={handleInputChange}
                  className="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                  placeholder="City, Country"
                />
              </div>
            </div>
          </div>

          <div className="pt-2 border-t border-gray-100">
             <label className="block text-sm font-medium text-gray-700 mb-2">Select Course</label>
             <div className="relative">
                <select
                  name="courseId"
                  required
                  value={formData.courseId}
                  onChange={handleInputChange}
                  className="w-full pl-4 pr-10 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all bg-white appearance-none"
                >
                  <option value="">-- Choose a Course --</option>
                  {courses.map(course => (
                    <option key={course.id} value={course.id}>{course.title}</option>
                  ))}
                </select>
                <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                  <svg className="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
             </div>
          </div>

          {/* Dynamic Course Details */}
          {selectedCourse && (
            <div className="bg-indigo-50 border border-indigo-100 rounded-lg p-4 animate-in fade-in slide-in-from-top-2 duration-300">
              <div className="flex justify-between items-center mb-2">
                <div className="flex items-center gap-2 text-indigo-800">
                  <Clock size={18} />
                  <span className="font-medium">Duration:</span> {selectedCourse.duration}
                </div>
                <div className="flex items-center gap-2 text-green-700 font-bold text-lg">
                  <IndianRupee size={20} />
                  {selectedCourse.amount}
                </div>
              </div>
              <p className="text-xs text-indigo-400 mt-2">* Includes all study materials and certification</p>
            </div>
          )}

          <button
            type="submit"
            disabled={submitting || !selectedCourse}
            className="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
          >
            {submitting ? 'Processing...' : (
              <>
                Proceed to Payment <ArrowRight size={20} />
              </>
            )}
          </button>
        </form>
      </div>
    </div>
  );
}
