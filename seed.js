use investhub;
db.users.insertMany([
  {name: 'Admin', email: 'admin@investhub.et', password_hash: '', role: 'admin', created_at: new Date()},
  {name: 'Entrepreneur', email: 'entrepreneur@investhub.et', password_hash: '', role: 'entrepreneur', created_at: new Date()},
  {name: 'Investor', email: 'investor@investhub.et', password_hash: '', role: 'investor', created_at: new Date()}
]);
db.blog.insertMany([
  {title: 'First Blog Post', content: 'Welcome to InvestHub!', created_at: new Date()}
]);
db.faq.insertMany([
  {question: 'How does InvestHub work?', answer: 'Connects investors with entrepreneurs.'}
]);
db.investments.insertOne({
  user_id: db.users.findOne({email: "entrepreneur@investhub.et"})._id,
  title: "Sample Investment Opportunity",
  description: "A great opportunity.",
  sector: "Tech",
  funding_needed: 50000,
  status: "approved",
  created_at: new Date()
});