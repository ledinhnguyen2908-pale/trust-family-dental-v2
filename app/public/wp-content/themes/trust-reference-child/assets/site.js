(() => {
 'use strict';
 const header=document.querySelector('.hdr');
 const landingForm=document.querySelector('#lp1-form, #lp2-form, #lp3-form');
 const landingSlug=window.location.pathname.split('/').filter(Boolean).pop();
 if(landingForm && ['cant-wait','restorative','family'].includes(landingSlug)){
  landingForm.addEventListener('wpcf7mailsent',event=>{
   const inputs=event.detail?.inputs;
   const office=Array.isArray(inputs)?inputs.find(input=>input.name==='location')?.value:'';
   if(office!=='aurora' && office!=='denver')return;
   window.dataLayer=window.dataLayer||[];
   window.dataLayer.push({event:'lead_submit',lp:landingSlug,location:office});
  });
 }
 document.querySelectorAll('a[data-form-office]').forEach(link=>{
  link.addEventListener('click',event=>{
   if(event.ctrlKey || event.metaKey || event.shiftKey || event.altKey)return;
   const section=document.getElementById(link.hash.slice(1));
   const location=section?.querySelector('.wpcf7 select[name="location"]');
   if(!location || !Array.from(location.options).some(option=>option.value===link.dataset.formOffice))return;
   location.value=link.dataset.formOffice;
   location.dispatchEvent(new Event('change',{bubbles:true}));
   requestAnimationFrame(()=>location.focus());
  });
 });
 const toggle=header?.querySelector('.burger');
 const close=()=>{header?.classList.remove('open');toggle?.setAttribute('aria-expanded','false');};
 toggle?.addEventListener('click',()=>{const open=!header.classList.contains('open');header.classList.toggle('open',open);toggle.setAttribute('aria-expanded',String(open));});
 document.addEventListener('keydown',event=>{if(event.key==='Escape'&&header?.classList.contains('open')){close();toggle.focus();}});
 document.addEventListener('click',event=>{if(header&&!header.contains(event.target))close();});
 document.querySelectorAll('.mnav a').forEach(link=>link.addEventListener('click',close));
 document.querySelectorAll('[data-load-map]').forEach(button=>button.addEventListener('click',()=>{
  const box=button.closest('[data-map-url]'); if(!box?.dataset.mapUrl)return;
  const url=new URL(box.dataset.mapUrl);if(url.protocol!=='https:'||!(url.hostname==='google.com'||url.hostname.endsWith('.google.com')))return;
  const frame=document.createElement('iframe');frame.src=url.href;frame.title=document.documentElement.lang.startsWith('es')?'Mapa de la clínica':'Office map';frame.loading='lazy';frame.referrerPolicy='no-referrer-when-downgrade';box.replaceChildren(frame);
 },{once:true}));

 // Motion is an enhancement: content is never hidden while waiting for JS or an observer.
 const reducedMotion=window.matchMedia('(prefers-reduced-motion: reduce)');
 const running=new Set();
 let revealObserver;
 const animate=(element,frames,options)=>{
  if(reducedMotion.matches || !element.animate)return;
  const animation=element.animate(frames,options);
  running.add(animation);
  const release=()=>running.delete(animation);
  animation.finished.then(release,release);
 };
 if('IntersectionObserver' in window && !reducedMotion.matches){
  revealObserver=new IntersectionObserver(entries=>{
   const entering=entries.filter(entry=>entry.isIntersecting);
   entering.forEach((entry,index)=>{
    revealObserver.unobserve(entry.target);
    animate(entry.target,[{opacity:.18,transform:'translateY(22px)'},{opacity:1,transform:'none'}],{
     duration:650,delay:Math.min(index,3)*65,easing:'cubic-bezier(.22,1,.36,1)'
    });
   });
  },{threshold:0,rootMargin:'0px 0px -24px 0px'});
  const candidates=document.querySelectorAll('main .eyebrow,main .h2,main .reasons>a,main .rows>.row,main .svc,main .cols3>div,main .cols4>div,main .team>div,main .rv>div,main .loclist>div,main .loc-compact>div,main .faq details,main .sec .ph,main .closing h2');
  candidates.forEach(element=>{
   // Do not replay restored pages, hash destinations or above-the-fold content.
   if(element.getBoundingClientRect().top>window.innerHeight)revealObserver.observe(element);
  });
 }
 reducedMotion.addEventListener('change',()=>{
  if(reducedMotion.matches){revealObserver?.disconnect();running.forEach(animation=>animation.cancel());running.clear();}
 });
 document.addEventListener('focusin',event=>{
  // Keyboard navigation must never wait for an entrance animation.
  running.forEach(animation=>{if(animation.effect?.target?.contains(event.target))animation.cancel();});
 });
 window.addEventListener('pageshow',event=>{
  if(event.persisted){revealObserver?.disconnect();running.forEach(animation=>animation.cancel());}
 });
 // One passive, frame-throttled listener; no scroll hijacking or navigation delay.
 let scrollFrame=0;
 const updateHeader=()=>{
  scrollFrame=0;
  if(!header)return;
  const range=document.documentElement.scrollHeight-window.innerHeight;
  header.classList.toggle('is-scrolled',window.scrollY>40);
  header.style.setProperty('--reading-progress',String(range>0?Math.min(1,Math.max(0,window.scrollY/range)):0));
 };
 window.addEventListener('scroll',()=>{if(!scrollFrame)scrollFrame=requestAnimationFrame(updateHeader);},{passive:true});
 window.addEventListener('resize',()=>{if(!scrollFrame)scrollFrame=requestAnimationFrame(updateHeader);},{passive:true});
 updateHeader();

 // Booking v3 time request picker. These are request times until a live PMS integration is connected.
 const bookingDays=document.querySelector('[data-booking-days]');
 const bookingSlots=document.querySelector('[data-booking-slots]');
 if(bookingDays&&bookingSlots){
  const form=bookingDays.closest('form');
  const dateField=form?.querySelector('[name="preferred-day"]');
  const timeField=form?.querySelector('[name="preferred-time"]');
  const spanish=document.documentElement.lang.toLowerCase().startsWith('es');
  const locale=spanish?'es-US':'en-US';
  const times=['9:00 AM','10:00 AM','11:30 AM','1:00 PM','2:30 PM','4:00 PM'];
  const setPressed=(group,active)=>group.querySelectorAll('button').forEach(button=>{
   const on=button===active;button.classList.toggle('on',on);button.setAttribute('aria-pressed',String(on));
  });
  const isoDate=date=>`${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
  const today=new Date();today.setHours(12,0,0,0);
  for(let index=0;index<6;index+=1){
   const date=new Date(today);date.setDate(today.getDate()+index);
   const button=document.createElement('button');button.type='button';button.dataset.value=isoDate(date);
   const top=index===0?(spanish?'Hoy':'Today'):new Intl.DateTimeFormat(locale,{weekday:'short'}).format(date);
   const bottom=new Intl.DateTimeFormat(locale,{month:'short',day:'numeric'}).format(date);
   button.innerHTML=`<b>${top}</b><span>${bottom}</span>`;button.setAttribute('aria-label',new Intl.DateTimeFormat(locale,{weekday:'long',month:'long',day:'numeric'}).format(date));
   button.addEventListener('click',()=>{setPressed(bookingDays,button);if(dateField)dateField.value=button.dataset.value;});
   bookingDays.append(button);if(index===0)button.click();
  }
  times.forEach((time,index)=>{
   const button=document.createElement('button');button.type='button';button.textContent=time;button.dataset.value=time;
   button.addEventListener('click',()=>{setPressed(bookingSlots,button);if(timeField)timeField.value=time;});
   bookingSlots.append(button);if(index===1)button.click();
  });
 }
})();
